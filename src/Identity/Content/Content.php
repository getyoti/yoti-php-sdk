<?php

namespace Yoti\Identity\Content;

use Yoti\Exception\EncryptedDataException;

class Content
{
    private ?string $profile;
    private ?string $extraData;

    public function __construct(?string $profile = null, ?string $extraData = null)
    {
        $this->profile = $profile;
        $this->extraData = $extraData;
    }

    public function getProfile(): ?string
    {
        if (null !== $this->profile) {
            $decoded = base64_decode($this->profile, true);
            if ($decoded === false) {
                throw new EncryptedDataException('Could not decode data');
            }

            return $decoded;
        }

        return null;
    }

    public function getExtraData(): ?string
    {
        if (null !== $this->extraData) {
            $decoded = base64_decode($this->extraData, true);
            if ($decoded === false) {
                throw new EncryptedDataException('Could not decode data');
            }

            return $decoded;
        }

        return null;
    }
}
