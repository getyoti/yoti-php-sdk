<?php

// Make sure you run composer update inside the example folder before trying this example out
require_once __DIR__ .'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Load environment variables
try {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/.env');

} catch(\Exception $e) {
    $errorMessage = "Error loading env variables - {$e->getMessage()}";
    die($errorMessage . PHP_EOL);
}

// Get SDK ID.
define('YOTI_SDK_ID', getenv('YOTI_SDK_ID'));

// Resolve key path.
define('YOTI_KEY_FILE_PATH_KEY', 'YOTI_KEY_FILE_PATH');
if (strpos(getenv(YOTI_KEY_FILE_PATH_KEY), '/') === 0) {
    define(YOTI_KEY_FILE_PATH_KEY, getenv(YOTI_KEY_FILE_PATH_KEY));
} else {
    define(YOTI_KEY_FILE_PATH_KEY, __DIR__ . '/' . trim(getenv(YOTI_KEY_FILE_PATH_KEY), './'));
}

