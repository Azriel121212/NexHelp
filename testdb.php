<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
try {
    DB::statement('
        CREATE TABLE IF NOT EXISTS transactions (
            id BIGSERIAL PRIMARY KEY,
            user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            type VARCHAR(50) NOT NULL,
            points INT NOT NULL,
            amount INT NOT NULL,
            status VARCHAR(50) DEFAULT \'success\',
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NULL
        )
    ');
    echo "Transactions table created successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
