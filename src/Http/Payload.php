<?php

declare(strict_types=1);

namespace Yoti\Http;

use Psr\Http\Message\StreamInterface;

use function GuzzleHttp\Psr7\stream_for;

class Payload
{
    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    private $stream;

    /**
     * @param \Psr\Http\Message\StreamInterface $stream
     */
    public function __construct(StreamInterface $stream = null)
    {
        $this->stream = $stream;
    }

    /**
     * Get Base64 encoded of payload string.
     *
     * @return string
     */
    public function toBase64(): string
    {
        return base64_encode((string) $this->stream);
    }

    /**
     * @param mixed $jsonData
     *
     * @return \Yoti\Http\Payload
     */
    public static function fromJsonData($jsonData): self
    {
        return static::fromString(json_encode($jsonData));
    }

    /**
     * @param string $string
     *
     * @return \Yoti\Http\Payload
     */
    public static function fromString(string $string): self
    {
        return static::fromStream(stream_for($string));
    }

    /**
     * @param \Psr\Http\Message\StreamInterface $stream
     *
     * @return \Yoti\Http\Payload
     */
    public static function fromStream(StreamInterface $stream): self
    {
        return new static($stream);
    }

    /**
     * Get payload as stream.
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function toStream(): StreamInterface
    {
        return $this->stream;
    }

    /**
     * Get payload as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->stream;
    }
}
