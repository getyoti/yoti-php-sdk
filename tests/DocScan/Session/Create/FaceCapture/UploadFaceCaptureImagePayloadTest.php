<?php

namespace Yoti\Test\DocScan\Session\Create\FaceCapture;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\FaceCapture\UploadFaceCaptureImagePayload;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\FaceCapture\UploadFaceCaptureImagePayload
 */
class UploadFaceCaptureImagePayloadTest extends TestCase
{
    private const SOME_IMAGE_CONTENTS = "SOME_CONTENTS";
    private const SOME_IMAGE_CONTENT_TYPE = 'img/some';

    /**
     * @test
     * @covers ::getImageContentType
     * @covers ::getImageContents
     * @covers ::__construct
     */
    public function shouldCorrectlyUploadFaceCaptureImagePayloadWithJpegContentType()
    {
        $result = new UploadFaceCaptureImagePayload(
            self::SOME_IMAGE_CONTENT_TYPE,
            self::SOME_IMAGE_CONTENTS
        );
        Assert::assertEquals(self::SOME_IMAGE_CONTENT_TYPE, $result->getImageContentType());
        Assert::assertEquals(self::SOME_IMAGE_CONTENTS, $result->getImageContents());
    }
}
