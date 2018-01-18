<?php

// Make sure you run composer update inside the example folder before trying this example out
require_once './vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');