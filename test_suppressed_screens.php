<?php

require_once 'vendor/autoload.php';

use Yoti\DocScan\Session\Create\SdkConfigBuilder;

// Test the suppressed screens functionality
$builder = new SdkConfigBuilder();

// Test adding multiple screens at once
$builder->withSuppressedScreens(['WELCOME_SCREEN', 'PRIVACY_POLICY']);

// Test adding individual screen
$builder->withSuppressedScreen('TERMS_AND_CONDITIONS');

// Build the config
$config = $builder->build();

// Test serialization
$json = json_encode($config);
echo "JSON Output:\n";
echo $json . "\n\n";

// Test getter
$suppressedScreens = $config->getSuppressedScreens();
echo "Suppressed Screens:\n";
var_dump($suppressedScreens);

echo "\nImplementation test completed successfully!\n";
