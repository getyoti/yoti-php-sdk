<?php

namespace Yoti\Identity\Content;

use Yoti\Profile\ExtraData;
use Yoti\Profile\UserProfile;

class UserContent
{
    private ?UserProfile $profile;
    private ?ExtraData $extraData;

    public function __construct(?UserProfile $profile = null, ?ExtraData $extraData = null)
    {
        $this->profile = $profile;
        $this->extraData = $extraData;
    }

    public function getProfile(): ?UserProfile
    {
        return $this->profile;
    }

    public function getExtraData(): ?ExtraData
    {
        return $this->extraData;
    }
}
