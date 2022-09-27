<?php

namespace Yoti\Test\Http;

use Psr\Http\Message\StreamInterface;
use Yoti\Http\MultipartEntity;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\MultipartEntity
 */
class MultipartEntityTest extends TestCase
{
    /**
     * @test
     * @covers ::create
     */
    public function shouldCorrectlyCreateObjectTest()
    {
        $newEntity = MultipartEntity::create();

        $this->assertInstanceOf(MultipartEntity::class, $newEntity);
    }

    /**
     * @test
     * @covers ::setBoundary
     * @covers ::getMultipartBoundary
     * @covers ::addBinaryBody
     * @covers ::getMultipartData
     * @covers ::createStream
     */
    public function shouldCorrectCreateStream()
    {
        $name = 'SOME_NAME';
        $payload = 'SOME_PAYLOAD';
        $contentType = 'SOME_TYPE';
        $fileName = 'SOME_FILENAME';

        $multipartBoundary = 'SOME';

        $multipartData = [
            [
                'name' => $name,
                'contents' => $payload,
                'filename' => $fileName,
                'headers' => ['Content-Type' => $contentType]
            ]
        ];


        $newEntity = MultipartEntity::create();
        $newEntity->setBoundary($multipartBoundary);
        $newEntity->addBinaryBody($name, $payload, $contentType, $fileName);

        $stream = $newEntity->createStream();

        $this->assertEquals($multipartBoundary, $newEntity->getMultipartBoundary());
        $this->assertEquals($multipartData, $newEntity->getMultipartData());
        $this->assertInstanceOf(StreamInterface::class, $stream);
    }
}
