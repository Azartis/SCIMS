<?php

require __DIR__.'/../vendor/autoload.php';

// Boot the framework to use facades and DB
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

if (! Schema::hasTable('cache')) {
    Schema::create('cache', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->string('key')->primary();
        $table->mediumText('value');
        $table->integer('expiration')->index();
    });
    echo "cache table created\n";
} else {
    echo "cache table already exists\n";
}
