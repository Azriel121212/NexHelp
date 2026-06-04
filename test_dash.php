<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::where('email', 'admin@example.com')->first();
if (!$user) {
    $user = \App\Models\User::first();
}
if ($user) {
    $user->is_admin = true;
    $user->save();
}

Auth::login($user);

$request = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
$response = $kernel->handle($request);

echo $response->getContent();
