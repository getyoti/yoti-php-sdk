<?php

namespace YotiTest;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Restores ini settings after tests run.
     */
    public function teardown()
    {
        parent::teardown();
        ini_restore('error_log');
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
    }

    /**
     * Asserts that the log file contains the provided string.
     *
     * @param string $str
     */
    protected function assertLogContains($str)
    {
        $this->assertFileExists(ini_get('error_log'));
        $this->assertContains($str, file_get_contents(ini_get('error_log')));
    }
}
