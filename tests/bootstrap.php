<?php

/**
 * loads all required files including composer vendor autoload
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

/**
 * Disable deprecated-to-exception conversion in PHP 7.4
 *
 * PHPUnit 5.7 is needed whilst we support PHP 5.6, but
 * uses ReflectionType::__toString(), which will throw
 * an exception as of PHP 7.4
 */
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    PHPUnit_Framework_Error_Deprecated::$enabled = false;
}
