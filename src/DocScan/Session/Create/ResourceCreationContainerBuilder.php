<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

class ResourceCreationContainerBuilder
{
    /**
     * @var ApplicantProfile|null
     */
    private $applicantProfile;

    /**
     * @param ApplicantProfile $applicantProfile
     * @return $this
     */
    public function withApplicantProfile(ApplicantProfile $applicantProfile): self
    {
        $this->applicantProfile = $applicantProfile;
        return $this;
    }

    /**
     * @return ResourceCreationContainer
     */
    public function build(): ResourceCreationContainer
    {
        return new ResourceCreationContainer(
            $this->applicantProfile
        );
    }
}
