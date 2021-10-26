<?php

namespace Yoti\Test\DocScan\Session\Create\FaceCapture;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\FaceCapture\CreateFaceCaptureResourcePayload;
use Yoti\DocScan\Session\Create\FaceCapture\CreateFaceCaptureResourcePayloadBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\FaceCapture\CreateFaceCaptureResourcePayloadBuilder
 */
class CreateFaceCaptureResourcePayloadBuilderTest extends TestCase
{
    private const SOME_REQUIREMENT_ID = 'someRequirementId';

    /**
     * @test
     * @covers ::withRequirementId
     * @covers ::build
     */
    public function shouldBuildCreateFaceCaptureResourcePayloadCorrectly()
    {
        $result = (new CreateFaceCaptureResourcePayloadBuilder())
            ->withRequirementId(self::SOME_REQUIREMENT_ID)
            ->build();

        Assert::assertInstanceOf(CreateFaceCaptureResourcePayload::class, $result);
    }
}
