<?php
$tmpDir = '/tmp/laravel';

// Create required directories in /tmp
$directories = [
    "$tmpDir/storage/framework/views",
    "$tmpDir/storage/framework/cache",
    "$tmpDir/storage/framework/cache/data",
    "$tmpDir/storage/framework/sessions",
    "$tmpDir/bootstrap/cache",
    "$tmpDir/storage/logs",
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Set environment variables to point to /tmp
$_ENV['VIEW_COMPILED_PATH'] = "$tmpDir/storage/framework/views";
$_ENV['APP_SERVICES_CACHE'] = "$tmpDir/bootstrap/cache/services.php";
$_ENV['APP_PACKAGES_CACHE'] = "$tmpDir/bootstrap/cache/packages.php";
$_ENV['APP_CONFIG_CACHE'] = "$tmpDir/bootstrap/cache/config.php";
$_ENV['APP_ROUTES_CACHE'] = "$tmpDir/bootstrap/cache/routes-v7.php";
$_ENV['APP_EVENTS_CACHE'] = "$tmpDir/bootstrap/cache/events.php";
$_ENV['LARAVEL_STORAGE_PATH'] = "$tmpDir/storage";

putenv("VIEW_COMPILED_PATH={$_ENV['VIEW_COMPILED_PATH']}");
putenv("LARAVEL_STORAGE_PATH={$_ENV['LARAVEL_STORAGE_PATH']}");

require __DIR__ . '/../public/index.php';
