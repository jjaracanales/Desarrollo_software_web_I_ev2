<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check if vendor directory exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    http_response_code(503);
    exit('Sistema en configuración. Intenta en unos minutos.');
}

// Register the Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Generate a proper Laravel application key (32 bytes for AES-256-CBC)
$appKey = 'base64:' . base64_encode(random_bytes(32));

// Set minimal environment for Vercel
putenv('APP_ENV=production');
putenv('APP_DEBUG=false'); // Disable debug in production
putenv('APP_KEY=' . $appKey);
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=/tmp/database.sqlite');
putenv('CACHE_DRIVER=array'); // Use array cache to avoid database cache issues
putenv('SESSION_DRIVER=array');
putenv('LOG_CHANNEL=stderr');

// CRITICAL: Set Laravel cache paths to /tmp (writable in Vercel)
putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');

// Set globals for Laravel
$_ENV['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = 'false'; // Disable debug in production
$_ENV['APP_KEY'] = $appKey;
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
$_ENV['CACHE_DRIVER'] = 'array'; // Ensure array cache

// Create minimal database
@touch('/tmp/database.sqlite');
@chmod('/tmp/database.sqlite', 0666);

// Create ALL necessary directories in /tmp (writable area)
$writableDirs = [
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache',
    '/tmp/storage',
    '/tmp/storage/logs',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/views'
];

foreach ($writableDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Create symlinks from Laravel directories to writable /tmp directories
$projectRoot = __DIR__ . '/..';
$symlinks = [
    $projectRoot . '/bootstrap/cache' => '/tmp/bootstrap/cache',
    $projectRoot . '/storage/logs' => '/tmp/storage/logs',
    $projectRoot . '/storage/framework/cache' => '/tmp/storage/framework/cache',
    $projectRoot . '/storage/framework/sessions' => '/tmp/storage/framework/sessions',
    $projectRoot . '/storage/framework/views' => '/tmp/storage/framework/views'
];

foreach ($symlinks as $link => $target) {
    if (!file_exists($link) && !is_link($link)) {
        @symlink($target, $link);
    }
}

try {
    // Bootstrap Laravel
    $app = require_once $projectRoot . '/bootstrap/app.php';
    
    if (!$app || !is_object($app)) {
        throw new Exception('Laravel app bootstrap failed');
    }

    // CRITICAL: Initialize database BEFORE handling any requests
    if (!file_exists('/tmp/db_ready')) {
        try {
            // Check if database file exists and is writable
            if (!file_exists('/tmp/database.sqlite')) {
                touch('/tmp/database.sqlite');
                chmod('/tmp/database.sqlite', 0666);
            }
            
            // Run ALL migrations (including cache, jobs, etc.)
            \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
            
            // Verify tables exist
            $pdo = new PDO('sqlite:/tmp/database.sqlite');
            $tables = ['proyectos', 'cache', 'jobs', 'users'];
            $allTablesExist = true;
            
            foreach ($tables as $table) {
                $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
                if (!$result || !$result->fetch()) {
                    $allTablesExist = false;
                }
            }
            
            if ($allTablesExist) {
                file_put_contents('/tmp/db_ready', 'complete');
            } else {
                throw new Exception('Some required tables were not created');
            }
            
        } catch (Exception $e) {
            error_log('Database initialization error: ' . $e->getMessage());
        }
    }

    // Handle the request
    $app->handleRequest(Request::capture());
    
} catch (Exception $e) {
    http_response_code(500);
    error_log('Laravel startup error: ' . $e->getMessage());
    exit('Error interno del servidor');
} 