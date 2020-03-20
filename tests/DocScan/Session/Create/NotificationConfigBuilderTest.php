<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\NotificationConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\NotificationConfigBuilder
 */
class NotificationConfigBuilderTest extends TestCase
{

    private const SOME_AUTH_TOKEN = 'someAuthToken';
    private const SOME_ENDPOINT = 'someEndpoint';
    private const SOME_TOPIC = 'someTopic';

    /**
     * @test
     * @covers ::build
     * @covers ::withAuthToken
     * @covers ::withEndpoint
     * @covers ::withTopic
     * @covers \Yoti\DocScan\Session\Create\NotificationConfig::__construct
     * @covers \Yoti\DocScan\Session\Create\NotificationConfig::getAuthToken
     * @covers \Yoti\DocScan\Session\Create\NotificationConfig::getEndpoint
     * @covers \Yoti\DocScan\Session\Create\NotificationConfig::getTopics
     */
    public function shouldBuildNotificationConfig()
    {
        $result = (new NotificationConfigBuilder())
            ->withAuthToken(self::SOME_AUTH_TOKEN)
            ->withEndpoint(self::SOME_ENDPOINT)
            ->withTopic(self::SOME_TOPIC)
            ->build();

        $this->assertEquals(self::SOME_AUTH_TOKEN, $result->getAuthToken());
        $this->assertEquals(self::SOME_ENDPOINT, $result->getEndpoint());

        $this->assertCount(1, $result->getTopics());
        $this->assertEquals(self::SOME_TOPIC, $result->getTopics()[0]);
    }

    /**
     * @test
     * @covers ::forResourceUpdate
     */
    public function shouldUseCorrectValueForResourceUpdate()
    {
        $result = (new NotificationConfigBuilder())
            ->withAuthToken(self::SOME_AUTH_TOKEN)
            ->withEndpoint(self::SOME_ENDPOINT)
            ->forResourceUpdate()
            ->build();

        $this->assertContains('RESOURCE_UPDATE', $result->getTopics());
    }

    /**
     * @test
     * @covers ::forTaskCompletion
     */
    public function shouldUseCorrectValueForTaskCompletion()
    {
        $result = (new NotificationConfigBuilder())
            ->withAuthToken(self::SOME_AUTH_TOKEN)
            ->withEndpoint(self::SOME_ENDPOINT)
            ->forTaskCompletion()
            ->build();

        $this->assertContains('TASK_COMPLETION', $result->getTopics());
    }

    /**
     * @test
     * @covers ::forCheckCompletion
     */
    public function shouldUseCorrectValueForCheckCompletion()
    {
        $result = (new NotificationConfigBuilder())
            ->withAuthToken(self::SOME_AUTH_TOKEN)
            ->withEndpoint(self::SOME_ENDPOINT)
            ->forCheckCompletion()
            ->build();

        $this->assertContains('CHECK_COMPLETION', $result->getTopics());
    }

    /**
     * @test
     * @covers ::forSessionCompletion
     */
    public function shouldUseCorrectValueForSessionCompletion()
    {
        $result = (new NotificationConfigBuilder())
            ->withAuthToken(self::SOME_AUTH_TOKEN)
            ->withEndpoint(self::SOME_ENDPOINT)
            ->forSessionCompletion()
            ->build();

        $this->assertContains('SESSION_COMPLETION', $result->getTopics());
    }

    /**
     * @test
     * @covers ::forResourceUpdate
     * @covers ::forTaskCompletion
     * @covers ::forCheckCompletion
     * @covers ::forSessionCompletion
     * @covers ::withTopic
     */
    public function shouldAllowAllNotificationTypes()
    {
        $result = (new NotificationConfigBuilder())
            ->withAuthToken(self::SOME_AUTH_TOKEN)
            ->withEndpoint(self::SOME_ENDPOINT)
            ->forResourceUpdate()
            ->forTaskCompletion()
            ->forCheckCompletion()
            ->forSessionCompletion()
            ->build();

        $this->assertCount(4, $result->getTopics());
        $this->assertContains('RESOURCE_UPDATE', $result->getTopics());
        $this->assertContains('TASK_COMPLETION', $result->getTopics());
        $this->assertContains('CHECK_COMPLETION', $result->getTopics());
        $this->assertContains('SESSION_COMPLETION', $result->getTopics());
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\NotificationConfig::jsonSerialize
     */
    public function shouldProduceCorrectOutput()
    {
        $result = (new NotificationConfigBuilder())
            ->withAuthToken(self::SOME_AUTH_TOKEN)
            ->withEndpoint(self::SOME_ENDPOINT)
            ->withTopic(self::SOME_TOPIC)
            ->build();

        $expected = [
            'auth_token' => self::SOME_AUTH_TOKEN,
            'endpoint' => self::SOME_ENDPOINT,
            'topics' => [
                self::SOME_TOPIC
            ]
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
