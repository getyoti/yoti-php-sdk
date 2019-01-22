<?php

// Make sure you run composer update inside the sandbox folder
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