<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$shops = \App\Models\Shop::all();
foreach ($shops as $s) {
    echo "ID: {$s->id} | Name: {$s->name} | Token: {$s->api_token}\n";
    $printers = \App\Models\Printer::where('shop_id', $s->id)->get();
    foreach ($printers as $p) {
        echo "   -> Printer: {$p->name} | System: {$p->system_printer_name} | Status: {$p->status} | Last: {$p->last_heartbeat_at}\n";
    }
}
