package com.mrdeveloper.printshebapayment;

import android.Manifest;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SwitchCompat;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class MainActivity extends AppCompatActivity {

    private static final int PERMISSION_REQ_CODE = 101;

    private TextView tvLiveBadge;
    private LinearLayout layoutPermissionAlert;
    private Button btnGrantPermission;
    private SwitchCompat switchService;
    private EditText etServerUrl;
    private EditText etApiToken;
    private Button btnSaveConfig;
    private TextView tvLogs;
    private TextView btnClearLogs;
    private TextView tvTestHeartbeat;
    private TextView tvServerStatusDot;
    private TextView tvServerStatusText;
    private TextView tvServerStatusBadge;

    private SharedPreferences prefs;

    private final BroadcastReceiver uiUpdateReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            loadTransactionLogs();
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Apply system window insets so content never overlaps with status bar or navigation bar
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, windowInsets) -> {
            Insets insets = windowInsets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(insets.left, insets.top, insets.right, insets.bottom);
            return WindowInsetsCompat.CONSUMED;
        });

        prefs = getSharedPreferences("PrintShebaPrefs", MODE_PRIVATE);

        initViews();
        loadConfig();
        checkPermissions();
        loadTransactionLogs();

        // Start Foreground Service if enabled
        if (switchService.isChecked()) {
            startPaymentService();
        }
    }

    private void initViews() {
        tvLiveBadge = findViewById(R.id.tvLiveBadge);
        layoutPermissionAlert = findViewById(R.id.layoutPermissionAlert);
        btnGrantPermission = findViewById(R.id.btnGrantPermission);
        switchService = findViewById(R.id.switchService);
        etServerUrl = findViewById(R.id.etServerUrl);
        etApiToken = findViewById(R.id.etApiToken);
        btnSaveConfig = findViewById(R.id.btnSaveConfig);
        tvLogs = findViewById(R.id.tvLogs);
        btnClearLogs = findViewById(R.id.btnClearLogs);
        tvTestHeartbeat = findViewById(R.id.tvTestHeartbeat);
        tvServerStatusDot = findViewById(R.id.tvServerStatusDot);
        tvServerStatusText = findViewById(R.id.tvServerStatusText);
        tvServerStatusBadge = findViewById(R.id.tvServerStatusBadge);

        btnGrantPermission.setOnClickListener(v -> requestAppPermissions());

        btnSaveConfig.setOnClickListener(v -> {
            saveConfig();
            checkServerConnection();
        });

        if (tvTestHeartbeat != null) {
            tvTestHeartbeat.setOnClickListener(v -> {
                saveConfig();
                checkServerConnection();
            });
        }

        switchService.setOnCheckedChangeListener((buttonView, isChecked) -> {
            prefs.edit().putBoolean("service_enabled", isChecked).apply();
            updateServiceStatus(isChecked);
            if (isChecked) {
                startPaymentService();
                Toast.makeText(this, "পেমেন্ট লিসেনার সার্ভিস সক্রিয় করা হয়েছে", Toast.LENGTH_SHORT).show();
            } else {
                stopPaymentService();
                Toast.makeText(this, "পেমেন্ট লিসেনার সার্ভিস বন্ধ করা হয়েছে", Toast.LENGTH_SHORT).show();
            }
        });

        btnClearLogs.setOnClickListener(v -> {
            prefs.edit().putString("transactions_log", "").apply();
            loadTransactionLogs();
            Toast.makeText(this, "লগ ক্লিয়ার করা হয়েছে", Toast.LENGTH_SHORT).show();
        });
    }

    private void loadConfig() {
        String serverUrl = prefs.getString("server_url", "http://10.0.2.2:8000");
        String apiToken = prefs.getString("api_token", "");
        boolean serviceEnabled = prefs.getBoolean("service_enabled", true);

        etServerUrl.setText(serverUrl);
        etApiToken.setText(apiToken);
        switchService.setChecked(serviceEnabled);
        updateServiceStatus(serviceEnabled);
        checkServerConnection();
    }

    private void checkServerConnection() {
        if (tvServerStatusText == null) return;
        tvServerStatusText.setText("সার্ভার সংযোগ যাচাই হচ্ছে...");
        tvServerStatusBadge.setText("যাচাই...");
        tvServerStatusBadge.setBackgroundResource(R.drawable.bg_status_badge_red);

        ApiClient.sendHeartbeat(this, new ApiClient.ApiCallback() {
            @Override
            public void onSuccess(String response) {
                runOnUiThread(() -> {
                    if (tvServerStatusText != null) {
                        tvServerStatusText.setText("সার্ভার: অনলাইন ও সংযুক্ত (HTTP 200)");
                        tvServerStatusText.setTextColor(0xFF34D399);
                        tvServerStatusBadge.setText("কানেক্টেড");
                        tvServerStatusBadge.setTextColor(0xFF34D399);
                        tvServerStatusBadge.setBackgroundResource(R.drawable.bg_status_badge);
                        tvServerStatusDot.setBackgroundResource(R.drawable.bg_status_badge);
                    }
                });
            }

            @Override
            public void onError(String error) {
                runOnUiThread(() -> {
                    if (tvServerStatusText != null) {
                        tvServerStatusText.setText("সংযোগ ত্রুটি: " + error);
                        tvServerStatusText.setTextColor(0xFFF87171);
                        tvServerStatusBadge.setText("ডিসকানেক্টেড");
                        tvServerStatusBadge.setTextColor(0xFFF87171);
                        tvServerStatusBadge.setBackgroundResource(R.drawable.bg_status_badge_red);
                        tvServerStatusDot.setBackgroundResource(R.drawable.bg_status_badge_red);
                    }
                });
            }
        });
    }

    private void saveConfig() {
        String url = etServerUrl.getText().toString().trim();
        String token = etApiToken.getText().toString().trim();

        if (url.isEmpty()) {
            Toast.makeText(this, "সার্ভার URL খালি রাখা যাবে না", Toast.LENGTH_SHORT).show();
            return;
        }

        prefs.edit()
                .putString("server_url", url)
                .putString("api_token", token)
                .apply();

        Toast.makeText(this, "সেটিংস সংরক্ষিত হয়েছে!", Toast.LENGTH_SHORT).show();
    }

    private void updateServiceStatus(boolean enabled) {
        if (enabled) {
            tvLiveBadge.setText("● সক্রিয়");
            tvLiveBadge.setTextColor(0xFF34D399);
            tvLiveBadge.setBackgroundResource(R.drawable.bg_status_badge);
        } else {
            tvLiveBadge.setText("● বন্ধ");
            tvLiveBadge.setTextColor(0xFFF87171);
            tvLiveBadge.setBackgroundResource(R.drawable.bg_status_badge_red);
        }
    }

    private void startPaymentService() {
        Intent serviceIntent = new Intent(this, PaymentForegroundService.class);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            startForegroundService(serviceIntent);
        } else {
            startService(serviceIntent);
        }
    }

    private void stopPaymentService() {
        Intent serviceIntent = new Intent(this, PaymentForegroundService.class);
        stopService(serviceIntent);
    }

    private void checkPermissions() {
        boolean notificationListenerEnabled = isNotificationServiceEnabled();
        boolean postNotifications = true;
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            postNotifications = ContextCompat.checkSelfPermission(this, Manifest.permission.POST_NOTIFICATIONS) == PackageManager.PERMISSION_GRANTED;
        }

        if (!notificationListenerEnabled || !postNotifications) {
            layoutPermissionAlert.setVisibility(View.VISIBLE);
        } else {
            layoutPermissionAlert.setVisibility(View.GONE);
        }
    }

    private boolean isNotificationServiceEnabled() {
        String pkgName = getPackageName();
        final String flat = android.provider.Settings.Secure.getString(getContentResolver(), "enabled_notification_listeners");
        if (flat != null && !flat.isEmpty()) {
            final String[] names = flat.split(":");
            for (String name : names) {
                if (name.contains(pkgName)) {
                    return true;
                }
            }
        }
        return false;
    }

    private void requestAppPermissions() {
        // 1. Request POST_NOTIFICATIONS if Android 13+
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            if (ContextCompat.checkSelfPermission(this, Manifest.permission.POST_NOTIFICATIONS) != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.POST_NOTIFICATIONS}, PERMISSION_REQ_CODE);
            }
        }

        // 2. Open Notification Listener Settings if not enabled
        if (!isNotificationServiceEnabled()) {
            Toast.makeText(this, "তালিকায় 'PrintSheba Payment Listener' খুঁজে চালু করে দিন", Toast.LENGTH_LONG).show();
            Intent intent = new Intent("android.settings.ACTION_NOTIFICATION_LISTENER_SETTINGS");
            startActivity(intent);
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == PERMISSION_REQ_CODE) {
            checkPermissions();
        }
    }

    private void triggerTestPayment(String method, double amount) {
        String token = etApiToken.getText().toString().trim();
        if (token.isEmpty()) {
            Toast.makeText(this, "প্রথমে শপ API টোকেন বসিয়ে সেভ করুন!", Toast.LENGTH_LONG).show();
            return;
        }

        PaymentParser.ParsedPayment testPayment = new PaymentParser.ParsedPayment();
        testPayment.method = method;
        testPayment.amount = amount;
        testPayment.senderPhone = "01711009988";
        testPayment.trxId = "TEST" + (System.currentTimeMillis() % 1000000);
        testPayment.isValid = true;

        Toast.makeText(this, "টেস্ট " + method.toUpperCase() + " পেমেন্ট পাঠানো হচ্ছে...", Toast.LENGTH_SHORT).show();

        ApiClient.postPayment(this, testPayment, new ApiClient.ApiCallback() {
            @Override
            public void onSuccess(String response) {
                runOnUiThread(() -> {
                    Toast.makeText(MainActivity.this, "✅ টেস্ট পেমেন্ট সফলভাবে সার্ভারে গৃহীত হয়েছে!", Toast.LENGTH_LONG).show();
                    saveLogEntry(testPayment.method.toUpperCase() + " টেস্ট: ৳" + testPayment.amount + " (TrxID: " + testPayment.trxId + ") [সাফল্য]");
                    loadTransactionLogs();
                });
            }

            @Override
            public void onError(String error) {
                runOnUiThread(() -> {
                    Toast.makeText(MainActivity.this, "❌ সার্ভার ত্রুটি: " + error, Toast.LENGTH_LONG).show();
                    saveLogEntry(testPayment.method.toUpperCase() + " টেস্ট ব্যর্থ: " + error);
                    loadTransactionLogs();
                });
            }
        });
    }

    private void saveLogEntry(String text) {
        String existing = prefs.getString("transactions_log", "");
        String timestamp = new SimpleDateFormat("hh:mm:ss a", Locale.getDefault()).format(new Date());
        String entry = "[" + timestamp + "] " + text + "\n";
        prefs.edit().putString("transactions_log", entry + existing).apply();
    }

    private void loadTransactionLogs() {
        String logs = prefs.getString("transactions_log", "");
        if (logs.trim().isEmpty()) {
            tvLogs.setText("এখনো কোনো ট্রানজেকশন পাওয়া যায়নি...\nবিকাশ/নগদ থেকে পেমেন্ট এসএমএস এলে স্বয়ংক্রিয়ভাবে এখানে তালিকা তৈরি হবে।");
        } else {
            tvLogs.setText(logs);
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        checkPermissions();
        loadTransactionLogs();
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            registerReceiver(uiUpdateReceiver, new IntentFilter("com.mrdeveloper.printshebapayment.UPDATE_UI"), RECEIVER_NOT_EXPORTED);
        } else {
            registerReceiver(uiUpdateReceiver, new IntentFilter("com.mrdeveloper.printshebapayment.UPDATE_UI"));
        }
    }

    @Override
    protected void onPause() {
        super.onPause();
        try {
            unregisterReceiver(uiUpdateReceiver);
        } catch (Exception ignored) { }
    }
}