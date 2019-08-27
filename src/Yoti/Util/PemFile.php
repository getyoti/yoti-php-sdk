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
     * @return PemFile
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
     * @return PemFile
     */
    public static function fromFilePath($filePath)
    {
        if (!is_file($filePath)) {
            throw new PemFileException('PEM file was not found.');
        }

        return new static(file_get_contents($filePath));
    }

    /**
     * Extracts the auth key from the pem file contents.
     *
     * @return string|null
     *
     * @throws PemFileException
     */
    public function getAuthKey()
    {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->content));
        if (!array_key_exists('key', $details)) {
            return null;
        }

        // Remove BEGIN RSA PRIVATE KEY / END RSA PRIVATE KEY lines
        $KeyStr = trim($details['key']);
        // Support line break on *nix systems, OS, older OS, and Microsoft
        $keyArr = preg_split('/\r\n|\r|\n/', $KeyStr);
        if (strpos($KeyStr, 'BEGIN') !== false) {
            array_shift($keyArr);
            array_pop($keyArr);
        }
        $authKey = implode('', $keyArr);

        // Check auth key is not empty
        if (empty($authKey)) {
            throw new PemFileException('Could not retrieve Auth key from PEM content.');
        }

        return $authKey;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}
