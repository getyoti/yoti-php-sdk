<?php
/**
 * If we aren't using the composer vendor auto loader then run this script to get all
 * required files
 */

// PHP Secure files
require_once __DIR__ . '/phpseclib/bootstrap.php';

// Autoload Yoti classes and other dependencies
spl_autoload_register(function($className) {
    if (strpos($className, 'Google\Protobuf') !== false || strpos($className, 'GPBMetadata') !== false) {
        $className = 'protobuflib\\' .  $className;
    }
    if (strpos($className, 'Yoti\Sharepubapi') === 0) {
        $className = substr_replace($className, '', 0, 5);
    }
    $file = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});
