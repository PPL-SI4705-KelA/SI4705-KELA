<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Simulate a request to /admin/chat/43
$request = Illuminate\Http\Request::create('/admin/chat/43', 'GET');

// Login as admin
$admin = App\Models\User::where('email', 'pardede281204@gmail.com')->first();
auth()->login($admin);

try {
    $response = $kernel->handle($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 400) {
        echo "Response content (first 500 chars): " . substr($response->getContent(), 0, 500) . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
}
