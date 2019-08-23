<?php

namespace Yoti\Util;

use Yoti\Exception\PemFileException;

class PemFile
{
    /**
     * Pem Content
     *
     * @var string
     */
    private $content;

    /**
     * @param string $content
     */
    public function __construct($content)
    {
        if (empty($content)) {
            throw new PemFileException('PEM content is required', 400);
        }

        if (!openssl_get_privatekey($content)) {
            throw new PemFileException('PEM content is invalid', 400);
        }

        $this->content = $content;
    }

    /**
     * Creates PemFile from string.
     *
     * @param string $content
     *
     * @return Yoti\Util\PemFile
     */
    public static function fromString($content)
    {
        return new static($content);
    }

    /**
     * Creates PemFile from file path.
     *
     * @param string $filePath
     *
     * @return Yoti\Util\PemFile
     */
    public static function fromFilePath($filePath)
    {
        // Assert file exists if user passed PEM file path using file:// stream wrapper.
        if (!is_file($filePath)) {
            throw new PemFileException('PEM file was not found.');
        }

        return new static(file_get_contents($filePath));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}
