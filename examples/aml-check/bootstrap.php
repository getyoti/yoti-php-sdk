<?php

// Make sure you run composer update inside the example folder before trying this example out
require_once __DIR__ .'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Load environment variables from .env file if present.
try {
    $envFilePath = __DIR__.'/.env';
    if (is_file($envFilePath)) {
        $dotenv = new Dotenv();
        $dotenv->load($envFilePath);
    }
} catch(\Exception $e) {
    $errorMessage = "Error loading env variables - {$e->getMessage()}";
    die($errorMessage . PHP_EOL);
}
