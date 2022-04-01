<?php

declare(strict_types=1);

namespace Yoti\Test;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    /**
     * @var callable[]
     */
    private static $mockFunctions;

    /**
     * @var bool
     */
    private $isCapturingLogs = false;

    public function setup(): void
    {
        parent::setup();

        self::$mockFunctions = [];
    }

    public function teardown(): void
    {
        parent::teardown();

        if ($this->isCapturingLogs) {
            unlink(ini_get('error_log'));
            $this->isCapturingLogs = false;
        }

        // Restores ini settings.
        ini_restore('error_log');
        ini_restore('display_errors');

        self::$mockFunctions = [];
    }

    /**
     * Mock a global function with provided callback function.
     *
     * The function being mocked must be implemented in the same namespace as the class
     * being tested and must return ::callMockFunction(__FUNCTION__, func_get_args());
     *
     * @param string $function
     * @param callable $callback
     */
    protected static function mockFunction($function, $callback): void
    {
        self::$mockFunctions[$function] = $callback;
    }

    /**
     * @param string $function
     * @param array $args
     *
     * @return mixed
     */
    public static function callMockFunction($function, $args = [])
    {
        $function_name_parts = explode('\\', $function);
        $function_name = array_pop($function_name_parts);
        $function = self::$mockFunctions[$function_name] ?? null;

        if ($function !== null) {
            return $function(...$args);
        }

        return call_user_func_array("\\{$function_name}", $args);
    }

    /**
     * Capture log output so that it can be inspected.
     */
    protected function captureExpectedLogs()
    {
        $logPath = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR . 'logs';

        if (!is_dir($logPath)) {
            mkdir($logPath);
        }
        ini_set('error_log', $logPath . DIRECTORY_SEPARATOR . uniqid('error_', true) . '.log');
        ini_set('display_errors', 'off');

        $this->isCapturingLogs = true;
    }

    /**
     * Asserts that the log file contains the provided string.
     *
     * @param string $str
     */
    protected function assertLogContains($str)
    {
        $this->assertFileExists(ini_get('error_log'));
        $this->assertStringContainsString($str, file_get_contents(ini_get('error_log')));
    }


    /**
     * Provides HTTP error status codes.
     */
    public function httpErrorStatusCodeProvider()
    {
        $clientCodes = [400, 401, 402, 403, 404];
        $serverCodes = [500, 501, 502, 503, 504];

        return array_map(
            function ($code) {
                return [$code];
            },
            $clientCodes + $serverCodes
        );
    }

    /**
     * Override assertMatchesRegularExpression to support older versions of PHPUnit.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     *
     * @return void
     */
    public static function assertMatchesRegularExpression(string $pattern, string $string, string $message = ''): void
    {
        if (method_exists(parent::class, __FUNCTION__)) {
            parent::{__FUNCTION__}(...\func_get_args());
        } else {
            parent::assertRegExp(...\func_get_args());
        }
    }
}
