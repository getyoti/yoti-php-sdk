<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\Content\ApplicationContent;
use Yoti\Identity\Content\UserContent;
use Yoti\Identity\Receipt;
use Yoti\Identity\ReceiptBuilder;
use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\UserProfile;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Receipt
 */
class ReceiptTest extends TestCase
{
    /**
     * @covers ::getExtraData
     * @covers ::getProfile
     * @covers ::getId
     * @covers ::getError
     * @covers ::getErrorReason
     * @covers ::getApplicationContent
     * @covers ::getTimestamp
     * @covers ::getParentRememberMeId
     * @covers ::getRememberMeId
     * @covers ::getSessionId
     * @covers ::getUserContent
     * @covers ::__construct
     */
    public function testShouldBuildCorrectly()
    {
        $someId = 'SOME_ID';
        $sessionId = 'SESSION_ID';
        $someTime = new \DateTime('2021-08-11 13:11:17');
        $applicationContent = $this->createMock(ApplicationContent::class);
        $userContent = $this->createMock(UserContent::class);
        $rememberId = 'SOME_REMEMBER_ID';
        $parentRememberId = 'SOME_PARENT_REMEMBER_ID';
        $someError = 'SOME_ERROR';
        $someErrorReason = 'SOME_ERROR_REASON';

        $receipt = new Receipt(
            $someId,
            $sessionId,
            $someTime,
            $applicationContent,
            $userContent,
            $rememberId,
            $parentRememberId,
            $someError,
            $someErrorReason
        );

        $this->assertEquals($someId, $receipt->getId());
        $this->assertEquals($sessionId, $receipt->getSessionId());
        $this->assertEquals($someTime, $receipt->getTimestamp());
        $this->assertEquals($applicationContent, $receipt->getApplicationContent());
        $this->assertEquals($userContent, $receipt->getUserContent());
        $this->assertEquals($rememberId, $receipt->getRememberMeId());
        $this->assertEquals($parentRememberId, $receipt->getParentRememberMeId());
        $this->assertEquals($someError, $receipt->getError());
        $this->assertEquals($someErrorReason, $receipt->getErrorReason());
    }

    /**
     * @covers \Yoti\Identity\ReceiptBuilder::withError
     * @covers \Yoti\Identity\ReceiptBuilder::withErrorReason
     * @covers \Yoti\Identity\ReceiptBuilder::withApplicationContent
     * @covers \Yoti\Identity\ReceiptBuilder::withId
     * @covers \Yoti\Identity\ReceiptBuilder::withTimestamp
     * @covers \Yoti\Identity\ReceiptBuilder::withSessionId
     * @covers \Yoti\Identity\ReceiptBuilder::withParentRememberMeId
     * @covers \Yoti\Identity\ReceiptBuilder::withRememberMeId
     * @covers \Yoti\Identity\ReceiptBuilder::withUserContent
     * @covers \Yoti\Identity\ReceiptBuilder::build
     */
    public function testShouldBuildCorrectlyThroughBuilder()
    {
        $someId = 'SOME_ID';
        $sessionId = 'SESSION_ID';
        $someTime = new \DateTime('2021-08-11 13:11:17');
        $userProfile = $this->createMock(UserProfile::class);
        $applicationProfile = $this->createMock(ApplicationProfile::class);
        $rememberId = 'SOME_REMEMBER_ID';
        $parentRememberId = 'SOME_PARENT_REMEMBER_ID';
        $someError = 'SOME_ERROR';
        $someErrorReason = 'SOME_ERROR_REASON';

        $receipt = (new ReceiptBuilder())
            ->withId($someId)
            ->withSessionId($sessionId)
            ->withTimestamp($someTime)
            ->withUserContent($userProfile)
            ->withApplicationContent($applicationProfile)
            ->withRememberMeId($rememberId)
            ->withParentRememberMeId($parentRememberId)
            ->withError($someError)
            ->withErrorReason($someErrorReason)
            ->build();

        $this->assertEquals($someId, $receipt->getId());
        $this->assertEquals($sessionId, $receipt->getSessionId());
        $this->assertEquals($someTime, $receipt->getTimestamp());
        $this->assertInstanceOf(ApplicationContent::class, $receipt->getApplicationContent());
        $this->assertInstanceOf(UserContent::class, $receipt->getUserContent());
        $this->assertEquals($rememberId, $receipt->getRememberMeId());
        $this->assertEquals($parentRememberId, $receipt->getParentRememberMeId());
        $this->assertEquals($someError, $receipt->getError());
        $this->assertEquals($someErrorReason, $receipt->getErrorReason());
    }
}
