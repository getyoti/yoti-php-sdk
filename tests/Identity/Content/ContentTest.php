<?php

namespace Yoti\Test\Identity\Content;

use Yoti\Exception\EncryptedDataException;
use Yoti\Identity\Content\Content;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Content\Content
 */
class ContentTest extends TestCase
{
    /**
     * @covers ::getExtraData
     * @covers ::getProfile
     * @covers ::__construct
     */
    public function testBuildCorrectly()
    {
        $someString = 'SOME_STRING_11111';
        $someString2 = 'f439fh9347h43uhfo34uhf';

        $content = new Content(base64_encode($someString), base64_encode($someString2));

        $this->assertEquals($someString, $content->getProfile());
        $this->assertEquals($someString2, $content->getExtraData());

        $content = new Content($someString, $someString2);

        $this->expectException(EncryptedDataException::class);

        $content->getProfile();
        $content->getExtraData();
    }
}
