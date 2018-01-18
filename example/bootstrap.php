<?php

// Make sure you run composer update inside the example folder before trying this example out
require_once './vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Load environment variables
try {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/.env');

} catch(\Exception $e) {
    $errorMessage = "Error loading env variables - {$e->getMessage()}";
    die($errorMessage . PHP_EOL);
}