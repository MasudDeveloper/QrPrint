<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce HTTPS in production or behind reverse proxies (Nginx/Cloudflare)
        // This is required for PWA installation, Service Worker and Web Share Target.
        if (app()->environment('production') || config('app.force_https') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Custom Branded Bengali Email for Password Reset
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('PrintSheba — পাসওয়ার্ড রিসেট করার লিঙ্ক')
                ->greeting('প্রিয় ' . ($notifiable->name ?? 'গ্রাহক') . ',')
                ->line('আপনার PrintSheba (প্রিন্ট সেবা) একাউন্টের পাসওয়ার্ড পরিবর্তনের একটি অনুরোধ পাওয়া গেছে।')
                ->action('পাসওয়ার্ড রিসেট করুন', $resetUrl)
                ->line('এই সিকিউর রিসেট লিঙ্কটির মেয়াদ আগামী ৬০ মিনিট পর্যন্ত কার্যকর থাকবে।')
                ->line('আপনি যদি এই অনুরোধটি না করে থাকেন, তবে কোনো পদক্ষেপ নেওয়ার প্রয়োজন নেই। আপনার একাউন্ট সম্পূর্ণ সুরক্ষিত আছে।')
                ->salutation('ধন্যবাদান্তে, PrintSheba টিম');
        });
    }
}
