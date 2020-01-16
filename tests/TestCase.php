<?php

declare(strict_types=1);

namespace YotiTest;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * Restores ini settings after tests run.
     */
    public function teardown(): void
    {
        parent::teardown();
        ini_restore('error_log');
        ini_restore('display_errors');
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
