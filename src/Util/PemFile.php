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
     * @param string $content
     *
     * @throws \Yoti\Exception\PemFileException
     */
    final public function __construct(string $content)
    {
        Validation::notEmptyString($content, 'content');

        if (openssl_get_privatekey($content) === false) {
            throw new PemFileException('PEM content is invalid');
        }

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
        if (!is_file($filePath)) {
            throw new PemFileException('PEM file was not found.');
        }

        $fileContents = file_get_contents($filePath);

        if ($fileContents === false) {
            throw new PemFileException('PEM file could not be read');
        }

        return static::fromString($fileContents);
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
        $resource = openssl_pkey_get_private($this->content);
        if ($resource === false) {
            throw new PemFileException('Could not get private key.');
        }

        $details = openssl_pkey_get_details($resource);
        if (!is_array($details) || !array_key_exists('key', $details)) {
            throw new PemFileException('PEM content does not contain a key.');
        }

        // Remove BEGIN RSA PRIVATE KEY / END RSA PRIVATE KEY lines
        $KeyStr = trim($details['key']);

        // Support line break on *nix systems, OS, older OS, and Microsoft
        $keyArr = preg_split('/\r\n|\r|\n/', $KeyStr);
        if (!is_array($keyArr)) {
            throw new PemFileException('PEM content does not contain new lines');
        }

        if (strpos($KeyStr, 'BEGIN') !== false) {
            array_shift($keyArr);
            array_pop($keyArr);
        }
        $authKey = implode('', $keyArr);

        // Check auth key is not empty
        if (strlen($authKey) === 0) {
            throw new PemFileException('Could not retrieve Auth key from PEM content.');
        }

        return $authKey;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}
