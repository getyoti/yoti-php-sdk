<?php

declare(strict_types=1);

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
     * Private Key.
     *
     * @var resource
     */
    private $privateKey;

    /**
     * @param string $content
     *
     * @throws \Yoti\Exception\PemFileException
     */
    final public function __construct(string $content)
    {
        Validation::notEmptyString($content, 'content');

        $privateKey = openssl_get_privatekey($content);
        if ($privateKey === false) {
            throw new PemFileException('PEM content is invalid');
        }

        $this->privateKey = $privateKey;
        $this->content = $content;
    }

    /**
     * Creates PemFile from string.
     *
     * @param string $content
     *
     * @return self
     *
     * @throws \Yoti\Exception\PemFileException
     */
    public static function fromString(string $content): self
    {
        return new static($content);
    }

    /**
     * Creates PemFile from file path.
     *
     * @param string $filePath
     *
     * @return self
     *
     * @throws \Yoti\Exception\PemFileException
     */
    public static function fromFilePath(string $filePath): self
    {
        if (
            is_file($filePath) &&
            ($fileContents = file_get_contents($filePath)) !== false
        ) {
            return static::fromString($fileContents);
        }

        throw new PemFileException('PEM file was not found.');
    }

    /**
     * @param string $pem
     *   PEM file path or string
     *
     * @return self
     *
     * @throws \Yoti\Exception\PemFileException
     */
    public static function resolveFromString(string $pem): self
    {
        Validation::notEmptyString($pem, 'pem');

        if (self::isPemString($pem)) {
            return static::fromString($pem);
        }

        return static::fromFilePath($pem);
    }

    /**
     * @param string $pem
     *
     * @return bool
     */
    private static function isPemString(string $pem): bool
    {
        return strpos(trim($pem), '-----BEGIN') === 0;
    }

    /**
     * Extracts the auth key from the pem file contents.
     *
     * @return string
     *
     * @throws \Yoti\Exception\PemFileException
     */
    public function getAuthKey(): string
    {
        $details = openssl_pkey_get_details($this->privateKey);
        if (!is_array($details) || !array_key_exists('key', $details)) {
            throw new PemFileException('Could not extract public key');
        }
        $publicKey = trim($details['key']);

        // Support line break on *nix systems, OS, older OS, and Microsoft
        $keyArr = preg_split('/\r\n|\r|\n/', $publicKey);

        if ((strpos($publicKey, 'BEGIN') !== false) && is_array($keyArr)) {
            // Remove BEGIN PUBLIC KEY / END PUBLIC KEY lines
            array_shift($keyArr);
            array_pop($keyArr);
            return implode('', $keyArr);
        }

        throw new PemFileException('Could not retrieve Auth key from PEM content.');
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}
