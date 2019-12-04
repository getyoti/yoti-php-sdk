<?php

/**
 * loads all required files including composer vendor autoload
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';


/**
 * Allow tests to run with:
 * - PHPUnit 5 for PHP 5.6
 * - PHPUnit 7 for PHP 7
 */
if (!class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias(\PHPUnit\Framework\TestCase::class, '\PHPUnit_Framework_TestCase');
}
