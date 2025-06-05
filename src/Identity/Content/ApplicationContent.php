<?php

namespace Yoti\Identity\Content;

use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\ExtraData;

class ApplicationContent
{
    private ?ApplicationProfile $profile;
    private ?ExtraData $extraData;

    public function __construct(?ApplicationProfile $profile = null, ?ExtraData $extraData = null)
    {
        $this->profile = $profile;
        $this->extraData = $extraData;
    }

    public function getProfile(): ?ApplicationProfile
    {
        return $this->profile;
    }

    public function getExtraData(): ?ExtraData
    {
        return $this->extraData;
    }
}
