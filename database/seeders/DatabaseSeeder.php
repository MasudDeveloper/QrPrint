<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'masud.info@gmail.com'],
            [
                'name' => 'MR Developer (সুপার এডমিন)',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '01644416378',
                'status' => 'active',
            ]
        );

        // 2. Initial Settings
        Setting::updateOrCreate(
            ['key' => 'homepage_video_url'],
            ['value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'] // Replaceable anytime from Super Admin panel
        );
        Setting::updateOrCreate(
            ['key' => 'homepage_video_title'],
            ['value' => 'কীভাবে ২ মিনিটে কাস্টমার নিজে নিজে প্রিন্ট করবে — লাইভ ভিডিও ডেমো']
        );
        Setting::updateOrCreate(
            ['key' => 'developer_url'],
            ['value' => 'https://mrdeveloper.xyz']
        );
        Setting::updateOrCreate(
            ['key' => 'hotline_number'],
            ['value' => '01644416378']
        );
        Setting::updateOrCreate(
            ['key' => 'support_email'],
            ['value' => 'masud.info@gmail.com']
        );

        // 3. Demo Shop Owner User & Shop
        $shopOwner = User::updateOrCreate(
            ['email' => 'shop@printsheba.com'],
            [
                'name' => 'আলমগীর হোসেন (দোকানদার)',
                'password' => Hash::make('password'),
                'role' => 'shop_owner',
                'phone' => '01711223344',
                'status' => 'active',
            ]
        );

        $shop = Shop::updateOrCreate(
            ['user_id' => $shopOwner->id],
            [
                'name' => 'মাইশা কম্পিউটার্স & ফটোকপি সেন্টার',
                'slug' => 'maisha-print',
                'address' => 'দোকান নং ১২, নিউ মার্কেট চত্বর, ঢাকা',
                'phone' => '01711223344',
                'bkash_number' => '01711223344',
                'nagad_number' => '01711223344',
                'rate_bw_single' => 3.00,
                'rate_bw_double' => 5.00,
                'rate_color_single' => 10.00,
                'rate_color_double' => 18.00,
                'rate_photo_passport' => 30.00,
                'api_token' => 'DEMO-AGENT-TOKEN-883921',
                'is_active' => true,
            ]
        );

        // 4. Sample Printers for shop
        Printer::updateOrCreate(
            ['shop_id' => $shop->id, 'name' => 'HP LaserJet Pro M404dn'],
            [
                'system_printer_name' => 'HP LaserJet Pro M404dn',
                'type' => 'bw',
                'is_default' => true,
                'status' => 'online',
                'last_heartbeat_at' => now(),
            ]
        );

        Printer::updateOrCreate(
            ['shop_id' => $shop->id, 'name' => 'Epson EcoTank L3210 (Color)'],
            [
                'system_printer_name' => 'EPSON L3210 Series',
                'type' => 'color',
                'is_default' => false,
                'status' => 'online',
                'last_heartbeat_at' => now(),
            ]
        );

        // 5. Sample completed print jobs
        PrintJob::updateOrCreate(
            ['job_token' => 'PS-DEMO001'],
            [
                'shop_id' => $shop->id,
                'customer_name' => 'রফিক সাহেব',
                'customer_phone' => '01812345678',
                'file_name' => 'NID_Card_Copy.pdf',
                'original_name' => 'NID_Card_Copy.pdf',
                'file_path' => 'demo/nid.pdf',
                'file_type' => 'pdf',
                'total_pages' => 2,
                'copies' => 1,
                'color_mode' => 'bw',
                'is_duplex' => true,
                'paper_size' => 'A4',
                'total_amount' => 5.00,
                'payment_method' => 'bkash',
                'payment_trx_id' => 'BKH8291048',
                'payment_status' => 'paid',
                'print_status' => 'printed',
                'printed_at' => now()->subMinutes(15),
            ]
        );

        PrintJob::updateOrCreate(
            ['job_token' => 'PS-DEMO002'],
            [
                'shop_id' => $shop->id,
                'customer_name' => 'তানজিলা রহমান',
                'customer_phone' => '01987654321',
                'file_name' => 'Passport_Photo_4x6.jpg',
                'original_name' => 'Photo.jpg',
                'file_path' => 'demo/photo.jpg',
                'file_type' => 'image/jpeg',
                'total_pages' => 1,
                'copies' => 1,
                'color_mode' => 'color',
                'paper_size' => '4x6_passport',
                'total_amount' => 30.00,
                'payment_method' => 'nagad',
                'payment_trx_id' => 'NGD9182741',
                'payment_status' => 'paid',
                'print_status' => 'printed',
                'printed_at' => now()->subMinutes(5),
            ]
        );
    }
}
