<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Util\DateTime;

class ShareCodeResourceResponse extends ResourceResponse
{
    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $lastUpdated;

    /**
     * @var MediaResponse|null
     */
    private $lookupProfileMedia;

    /**
     * @var MediaResponse|null
     */
    private $returnedProfileMedia;

    /**
     * @var MediaResponse|null
     */
    private $idPhotoMedia;

    /**
     * @var MediaResponse|null
     */
    private $fileMedia;

    /**
     * ShareCodeResourceResponse constructor.
     * @param array<string, mixed> $shareCode
     */
    public function __construct(array $shareCode)
    {
        parent::__construct($shareCode);

        $this->createdAt = isset($shareCode['created_at'])
            ? DateTime::stringToDateTime($shareCode['created_at'])
            : null;

        $this->lastUpdated = isset($shareCode['last_updated'])
            ? DateTime::stringToDateTime($shareCode['last_updated'])
            : null;

        $this->lookupProfileMedia = isset($shareCode['lookup_profile']['media'])
            ? new MediaResponse($shareCode['lookup_profile']['media'])
            : null;

        $this->returnedProfileMedia = isset($shareCode['returned_profile']['media'])
            ? new MediaResponse($shareCode['returned_profile']['media'])
            : null;

        $this->idPhotoMedia = isset($shareCode['id_photo']['media'])
            ? new MediaResponse($shareCode['id_photo']['media'])
            : null;

        $this->fileMedia = isset($shareCode['file']['media'])
            ? new MediaResponse($shareCode['file']['media'])
            : null;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUpdated(): ?\DateTime
    {
        return $this->lastUpdated;
    }

    /**
     * @return MediaResponse|null
     */
    public function getLookupProfileMedia(): ?MediaResponse
    {
        return $this->lookupProfileMedia;
    }

    /**
     * @return MediaResponse|null
     */
    public function getReturnedProfileMedia(): ?MediaResponse
    {
        return $this->returnedProfileMedia;
    }

    /**
     * @return MediaResponse|null
     */
    public function getIdPhotoMedia(): ?MediaResponse
    {
        return $this->idPhotoMedia;
    }

    /**
     * @return MediaResponse|null
     */
    public function getFileMedia(): ?MediaResponse
    {
        return $this->fileMedia;
    }

    /**
     * @return VerifyShareCodeTaskResponse[]
     */
    public function getVerifyShareCodeTasks(): array
    {
        return $this->filterTasksByType(VerifyShareCodeTaskResponse::class);
    }
}
