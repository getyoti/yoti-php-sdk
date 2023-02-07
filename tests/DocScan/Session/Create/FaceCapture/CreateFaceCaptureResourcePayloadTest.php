<?php

namespace Yoti\Test\IDV\Session\Create\FaceCapture;

use PHPUnit\Framework\Assert;
use Yoti\IDV\Session\Create\FaceCapture\CreateFaceCaptureResourcePayload;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\FaceCapture\CreateFaceCaptureResourcePayload
 */
class CreateFaceCaptureResourcePayloadTest extends TestCase
{
    private const SOME_REQUIREMENT_ID = 'someRequirementId';

    /**
     * @test
     * @covers ::getRequirementId
     * @covers ::__construct
     */
    public function shouldCreateFaceCaptureResourcePayloadCorrectly()
    {
        $result = new CreateFaceCaptureResourcePayload(self::SOME_REQUIREMENT_ID);

        Assert::assertEquals(self::SOME_REQUIREMENT_ID, $result->getRequirementId());
    }
}
