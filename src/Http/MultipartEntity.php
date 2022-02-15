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

    /**
     *  This constructor is private, because of you have to create objects only by static method 'create'
     */
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
            'contents' => json_encode($payload),
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

    /**
     * @return string
     */
    public function getMultipartBoundary(): string
    {
        return $this->multipartBoundary;
    }

    /**
     * @return array[]
     */
    public function getMultipartData(): array
    {
        return $this->multipartData;
    }
}
