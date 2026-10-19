<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CustomerPrintController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\SmsListenerController;
use App\Models\Setting;

/*
|--------------------------------------------------------------------------
| Public Homepage
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $videoUrl = Setting::get('homepage_video_url');
    $videoTitle = Setting::get('homepage_video_title', 'কীভাবে ২ মিনিটে কাস্টমার নিজে নিজে প্রিন্ট করবে — লাইভ ভিডিও ডেমো');
    return view('welcome', compact('videoUrl', 'videoTitle'));
})->name('home');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how_it_works');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'message' => 'required|string|max:1000',
    ]);
    return back()->with('success', 'ধন্যবাদ! আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে। আমাদের টিম খুব দ্রুত আপনার সাথে যোগাযোগ করবে।');
})->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Super Admin Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/live-status', [AdminController::class, 'liveStatus'])->name('live_status');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/shops', [AdminController::class, 'shops'])->name('shops');
    Route::post('/shops/{shop}/toggle', [AdminController::class, 'toggleShopStatus'])->name('shops.toggle');
    Route::post('/shops/{shop}/plan', [AdminController::class, 'updateShopPlan'])->name('shops.plan');
    Route::get('/shops/{shop}/login', [AdminController::class, 'loginAsShop'])->name('shops.login_as');
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::post('/subscriptions/{subscription}/approve', [AdminController::class, 'approveSubscription'])->name('subscriptions.approve');
    Route::post('/subscriptions/{subscription}/reject', [AdminController::class, 'rejectSubscription'])->name('subscriptions.reject');
    Route::get('/print-jobs', [AdminController::class, 'printJobs'])->name('jobs');
    Route::post('/jobs/{job}/approve', [AdminController::class, 'approvePayment'])->name('jobs.approve');
    Route::post('/jobs/{job}/reprint', [AdminController::class, 'reprintJob'])->name('jobs.reprint');
});

// Impersonation Revert Route
Route::get('/admin/revert-impersonation', [AdminController::class, 'revertImpersonation'])->name('admin.revert_impersonation');

/*
|--------------------------------------------------------------------------
| Shop Owner Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('shop')->name('shop.')->group(function () {
    Route::get('/dashboard', [ShopController::class, 'dashboard'])->name('dashboard');
    Route::get('/live-status', [ShopController::class, 'liveStatus'])->name('live_status');
    Route::post('/jobs/{job}/approve', [ShopController::class, 'approvePayment'])->name('jobs.approve');
    Route::match(['get', 'post'], '/jobs/{job}/reprint', [ShopController::class, 'reprintJob'])->name('jobs.reprint');
    Route::get('/jobs/{job}/slip', [ShopController::class, 'printJobSlip'])->name('jobs.slip');
    Route::get('/sync-status', [ShopController::class, 'syncStatus'])->name('sync_status');
    Route::get('/qr-poster', [ShopController::class, 'qrPoster'])->name('qr_poster');
    Route::get('/rates', [ShopController::class, 'rates'])->name('rates');
    Route::post('/rates', [ShopController::class, 'updateRates'])->name('rates.update');
    Route::get('/printers', [ShopController::class, 'printers'])->name('printers');
    Route::post('/printers', [ShopController::class, 'addPrinter'])->name('printers.add');
    Route::delete('/printers/{printer}', [ShopController::class, 'deletePrinter'])->name('printers.delete');
    Route::get('/subscription', [ShopController::class, 'subscription'])->name('subscription');
    Route::post('/subscription/purchase', [ShopController::class, 'purchaseSubscription'])->name('subscription.purchase');
    Route::get('/agent-setup', [ShopController::class, 'agentSetup'])->name('agent_setup');
    Route::get('/agent-download/zip', [ShopController::class, 'downloadAgentZip'])->name('agent_download_zip');
    Route::get('/agent-download/exe', [ShopController::class, 'downloadAgentExe'])->name('agent_download_exe');
    Route::get('/agent-download/config', [ShopController::class, 'downloadAgentConfig'])->name('agent_download_config');
    Route::get('/agent-download/apk', [ShopController::class, 'downloadAndroidApk'])->name('agent_download_apk');
});

// Direct Public Download with Token (For external Download Managers like FDM/IDM)
Route::get('/download/package/{token}', [ShopController::class, 'downloadAgentZipPublic'])->name('agent_download_zip_public');

/*
|--------------------------------------------------------------------------
| Customer Public Mobile Print Portal & Office Mode
|--------------------------------------------------------------------------
*/
Route::get('/print/{slug}', [CustomerPrintController::class, 'showUploadPage'])->name('customer.upload');
Route::post('/print/{slug}', [CustomerPrintController::class, 'submitJob'])->name('customer.submit');
Route::get('/print/{slug}/nid', [CustomerPrintController::class, 'showNidUploadPage'])->name('customer.nid_upload');
Route::post('/print/{slug}/nid', [CustomerPrintController::class, 'submitNidJob'])->name('customer.nid_submit');

// Dedicated Office Mode Routes (Zero payment, instant silent print for office workers)
Route::get('/office/{slug}', [CustomerPrintController::class, 'showOfficeUploadPage'])->name('customer.office_upload');
Route::post('/office/{slug}', [CustomerPrintController::class, 'submitOfficeJob'])->name('customer.office_submit');
Route::get('/office/{slug}/nid', [CustomerPrintController::class, 'showOfficeNidUploadPage'])->name('customer.office_nid_upload');
Route::post('/office/{slug}/nid', [CustomerPrintController::class, 'submitOfficeNidJob'])->name('customer.office_nid_submit');

Route::get('/print/status/{jobToken}', [CustomerPrintController::class, 'jobStatus'])->name('customer.status');
Route::get('/api/customer/status/{jobToken}', [CustomerPrintController::class, 'apiJobStatus']);

// AI Document Auto-Crop & Perspective Detection Routes
Route::post('/api/ai/auto-crop-document', [CustomerPrintController::class, 'autoCropDocument'])->name('api.ai.auto_crop');
Route::post('/api/ai/detect-corners', [CustomerPrintController::class, 'detectDocumentCorners'])->name('api.ai.detect_corners');

/*
|--------------------------------------------------------------------------
| Windows Agent & Android SMS Listener APIs
|--------------------------------------------------------------------------
*/
Route::prefix('api/agent')->group(function () {
    Route::get('/jobs', [AgentController::class, 'pollJobs']);
    Route::get('/jobs/{jobToken}/file', [AgentController::class, 'downloadJobFile']);
    Route::post('/jobs/{jobToken}/printed', [AgentController::class, 'markJobPrinted']);
    Route::post('/heartbeat', [AgentController::class, 'heartbeat']);
});

Route::post('/api/app/heartbeat', [SmsListenerController::class, 'appHeartbeat']);
Route::post('/api/webhook/sms-payment', [SmsListenerController::class, 'receiveSms']);

