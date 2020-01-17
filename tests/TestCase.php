<?php

declare(strict_types=1);

namespace YotiTest;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * @var callable[]
     */
    private static $mockFunctions;

    public function setup(): void
    {
        parent::setup();

        self::$mockFunctions = [];
    }

    public function teardown(): void
    {
        parent::teardown();

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
     *
     * @return callable|null
     */
    protected static function mockFunction($function, $callback)
    {
        return self::$mockFunctions[$function] = $callback;
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
        if (!is_dir('./logs')) {
            mkdir('./logs');
        }
        ini_set('error_log', './logs/' . uniqid('error_', true) . '.log');
        ini_set('display_errors', 'off');
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
}
