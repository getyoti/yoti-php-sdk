<?php

namespace Yoti\Http;

use GuzzleHttp\Psr7\MultipartStream;
use Psr\Http\Message\StreamInterface;

class MultipartEntity
{
    /**
     * @var string
     */
    private $multipartBoundary;

    /**
     * @var array<int, array<string,mixed>>
     */
    private $multipartData;

    private function __construct()
    {
    }

    /**
     * @return MultipartEntity
     */
    public static function create(): MultipartEntity
    {
        return new self();
    }

    /**
     * @param string $multipartBoundary
     */
    public function setBoundary(string $multipartBoundary): void
    {
        $this->multipartBoundary = $multipartBoundary;
    }

    /**
     * @param string $name
     * @param array<int,int> $payload
     * @param string $contentType
     * @param string $fileName
     *
     * @return void
     */
    public function addBinaryBody(string $name, array $payload, string $contentType, string $fileName): void
    {
        $this->multipartData[] = [
            'name' => $name,
            'contents' => $payload,
            'filename' => $fileName,
            'headers' => ['Content-type' => $contentType]
        ];
    }

    /**
     * @return StreamInterface
     */
    public function createStream(): StreamInterface
    {
        return new MultipartStream($this->multipartData, $this->multipartBoundary);
    }
}
