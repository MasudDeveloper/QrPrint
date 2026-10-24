<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\SystemErrorLog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class SystemCrashLogger
{
    /**
     * Keys to mask in logged payloads for security.
     */
    protected static array $sensitiveKeys = [
        'password',
        'password_confirmation',
        'token',
        '_token',
        'api_token',
        'secret',
        'agent_token',
        'credit_card',
        'cvv',
        'pin',
    ];

    /**
     * Log an uncaught or caught exception.
     */
    public static function logException(Throwable $e, ?string $level = null, array $extraContext = []): ?SystemErrorLog
    {
        try {
            // Determine severity level if not explicitly provided
            if (!$level) {
                if ($e instanceof \Error || $e instanceof \ParseError || $e instanceof \TypeError) {
                    $level = 'critical';
                } elseif ($e instanceof HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                    if ($status >= 500) {
                        $level = 'critical';
                    } elseif ($status === 404) {
                        $level = 'warning';
                    } elseif ($status >= 400) {
                        $level = 'warning';
                    } else {
                        $level = 'info';
                    }
                } else {
                    $level = 'error';
                }
            }

            $errorType = get_class($e);
            $message = $e->getMessage() ?: '(No message available)';
            $file = $e->getFile();
            $line = $e->getLine();
            $stackTrace = $e->getTraceAsString();

            return self::recordLog([
                'level' => $level,
                'error_type' => $errorType,
                'message' => $message,
                'file' => $file,
                'line' => $line,
                'stack_trace' => $stackTrace,
                'extra_context' => $extraContext,
            ]);
        } catch (Throwable $internalEx) {
            // Failsafe: never crash the application while trying to record a crash!
            try {
                Log::channel('single')->error('SystemCrashLogger internal failure: ' . $internalEx->getMessage());
            } catch (Throwable $ignored) {
            }
            return null;
        }
    }

    /**
     * Log a custom or domain-specific crash/error (e.g., printer agent crash, webhook failure).
     */
    public static function logCustomError(string $type, string $message, string $level = 'error', array $context = []): ?SystemErrorLog
    {
        try {
            $stackTrace = isset($context['stack_trace']) ? $context['stack_trace'] : null;
            $file = $context['file'] ?? null;
            $line = isset($context['line']) ? (int) $context['line'] : null;

            return self::recordLog([
                'level' => $level,
                'error_type' => $type,
                'message' => $message,
                'file' => $file,
                'line' => $line,
                'stack_trace' => $stackTrace,
                'extra_context' => $context,
            ]);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Internal method to record or deduplicate error log in database.
     */
    protected static function recordLog(array $data): ?SystemErrorLog
    {
        // Extract HTTP request context if in HTTP request
        $url = null;
        $method = null;
        $ip = null;
        $userAgent = null;
        $payloadJson = null;
        $shopId = $data['extra_context']['shop_id'] ?? null;
        $userId = $data['extra_context']['user_id'] ?? null;

        if (function_exists('request') && request()) {
            try {
                $req = request();
                $url = Str::limit($req->fullUrl(), 1000);
                $method = $req->method();
                $ip = $req->ip();
                $userAgent = Str::limit($req->userAgent(), 500);

                // Sanitize request inputs
                $allInputs = $req->all();
                if (!empty($allInputs)) {
                    $sanitized = self::sanitizeInputs($allInputs);
                    $payloadJson = json_encode($sanitized, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    if (strlen($payloadJson) > 65000) {
                        $payloadJson = Str::limit($payloadJson, 65000) . '... [TRUNCATED]';
                    }
                }

                // Associate user if authenticated
                if (!$userId && auth()->check()) {
                    $userId = auth()->id();
                }

                // Associate shop if available
                if (!$shopId) {
                    if (auth()->check() && isset(auth()->user()->shop_id)) {
                        $shopId = auth()->user()->shop_id;
                    } elseif (session()->has('shop_id')) {
                        $shopId = session('shop_id');
                    } elseif ($req->header('X-Agent-Token')) {
                        $token = $req->header('X-Agent-Token');
                        $shop = Shop::where('agent_token', $token)->first();
                        if ($shop) {
                            $shopId = $shop->id;
                        }
                    }
                }
            } catch (Throwable $reqEx) {
                // Request context extraction failsafe
            }
        }

        // Relative file path if inside base path for cleaner display
        $filePath = $data['file'];
        if ($filePath && str_starts_with($filePath, base_path())) {
            $filePath = ltrim(substr($filePath, strlen(base_path())), '/\\');
        }

        // Compute error hash for deduplication
        $hashSource = ($data['error_type'] ?? '') . '|' . ($filePath ?? '') . '|' . ($data['line'] ?? 0) . '|' . Str::substr($data['message'] ?? '', 0, 150);
        $errorHash = md5($hashSource);

        // Deduplication: check if unresolved log with identical hash exists
        $existing = SystemErrorLog::where('error_hash', $errorHash)
            ->where('status', 'unresolved')
            ->first();

        if ($existing) {
            $existing->increment('occurrences_count');
            $existing->update([
                'last_seen_at' => now(),
                'level' => $data['level'],
                'message' => $data['message'],
                'url' => $url ?: $existing->url,
                'ip_address' => $ip ?: $existing->ip_address,
                'user_agent' => $userAgent ?: $existing->user_agent,
                'payload' => $payloadJson ?: $existing->payload,
                'stack_trace' => $data['stack_trace'] ?: $existing->stack_trace,
            ]);
            return $existing;
        }

        return SystemErrorLog::create([
            'level' => $data['level'],
            'error_type' => $data['error_type'],
            'message' => $data['message'],
            'file' => $filePath,
            'line' => $data['line'],
            'url' => $url,
            'method' => $method,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'payload' => $payloadJson,
            'stack_trace' => $data['stack_trace'],
            'shop_id' => $shopId,
            'user_id' => $userId,
            'status' => 'unresolved',
            'error_hash' => $errorHash,
            'occurrences_count' => 1,
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Recursively mask sensitive fields.
     */
    protected static function sanitizeInputs(array $inputs): array
    {
        $sanitized = [];
        foreach ($inputs as $key => $value) {
            $lowerKey = strtolower($key);
            $isSensitive = false;
            foreach (self::$sensitiveKeys as $pattern) {
                if (str_contains($lowerKey, $pattern)) {
                    $isSensitive = true;
                    break;
                }
            }

            if ($isSensitive) {
                $sanitized[$key] = '******** [REDACTED]';
            } elseif (is_array($value)) {
                $sanitized[$key] = self::sanitizeInputs($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    /**
     * Read the tail of storage/logs/laravel.log safely.
     */
    public static function getRawLogs(int $maxLines = 250): array
    {
        $logPath = storage_path('logs/laravel.log');
        if (!File::exists($logPath)) {
            return [];
        }

        $lines = [];
        $file = @fopen($logPath, 'r');
        if (!$file) {
            return [];
        }

        // Read lines in reverse or tail
        $buffer = [];
        while (($line = fgets($file)) !== false) {
            $buffer[] = rtrim($line, "\r\n");
            if (count($buffer) > $maxLines) {
                array_shift($buffer);
            }
        }
        fclose($file);

        return array_reverse($buffer);
    }

    /**
     * Clear or truncate storage/logs/laravel.log.
     */
    public static function clearRawLogs(): bool
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            return (bool) @file_put_contents($logPath, '');
        }
        return true;
    }
}
