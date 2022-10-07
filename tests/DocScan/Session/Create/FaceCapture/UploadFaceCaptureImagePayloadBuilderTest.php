<?php

namespace Yoti\Test\DocScan\Session\Create\FaceCapture;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\FaceCapture\UploadFaceCaptureImagePayloadBuilder;
use Yoti\Media\Image\Jpeg;
use Yoti\Media\Image\Png;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\FaceCapture\UploadFaceCaptureImagePayloadBuilder
 */
class UploadFaceCaptureImagePayloadBuilderTest extends TestCase
{
    private const SOME_IMAGE_CONTENTS = "SOME_CONTENTS";

    /**
     * @test
     * @covers ::withImageContents
     * @covers ::forJpegImage
     * @covers ::build
     */
    public function shouldCorrectlyBuildUploadFaceCaptureImagePayloadWithJpegContentType()
    {
        $result = (new UploadFaceCaptureImagePayloadBuilder())
            ->withImageContents(self::SOME_IMAGE_CONTENTS)
            ->forJpegImage()
            ->build();

        Assert::assertEquals(self::SOME_IMAGE_CONTENTS, $result->getImageContents());
        Assert::assertEquals(Jpeg::MIME_TYPE, $result->getImageContentType());
    }

    /**
     * @test
     * @covers ::withImageContents
     * @covers ::forPngImage
     * @covers ::build
     */
    public function shouldCorrectlyBuildUploadFaceCaptureImagePayloadWithPngContentType()
    {
        $result = (new UploadFaceCaptureImagePayloadBuilder())
            ->withImageContents(self::SOME_IMAGE_CONTENTS)
            ->forPngImage()
            ->build();

        Assert::assertEquals(self::SOME_IMAGE_CONTENTS, $result->getImageContents());
        Assert::assertEquals(Png::MIME_TYPE, $result->getImageContentType());
    }
}
