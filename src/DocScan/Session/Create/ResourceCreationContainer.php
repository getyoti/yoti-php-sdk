<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use stdClass;
use Yoti\Util\Json;

class ResourceCreationContainer implements JsonSerializable
{
    /**
     * @var ApplicantProfile|null
     */
    private $applicantProfile;

    /**
     * @param ApplicantProfile|null $applicantProfile
     */
    public function __construct(?ApplicantProfile $applicantProfile)
    {
        $this->applicantProfile = $applicantProfile;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) Json::withoutNullValues([
            'applicant_profile' => $this->applicantProfile,
        ]);
    }

    /**
     * @return ApplicantProfile|null
     */
    public function getApplicantProfile(): ?ApplicantProfile
    {
        return $this->applicantProfile;
    }
}
