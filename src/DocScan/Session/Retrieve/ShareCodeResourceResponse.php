<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;
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
     * @var ShareCodeMediaResponse|null
     */
    private $lookupProfile;

    /**
     * @var ShareCodeMediaResponse|null
     */
    private $returnedProfile;

    /**
     * @var ShareCodeMediaResponse|null
     */
    private $idPhoto;

    /**
     * @var ShareCodeMediaResponse|null
     */
    private $file;

    /**
     * ShareCodeResourceResponse constructor.
     *
     * @param array<string, mixed> $shareCode
     *
     * @throws DateTimeException
     */
    public function __construct(array $shareCode)
    {
        parent::__construct($shareCode);

        $this->createdAt = isset($shareCode['created_at']) ?
            DateTime::stringToDateTime($shareCode['created_at']) : null;
        $this->lastUpdated = isset($shareCode['last_updated']) ?
            DateTime::stringToDateTime($shareCode['last_updated']) : null;

        $this->lookupProfile = isset($shareCode['lookup_profile'])
            ? new ShareCodeMediaResponse($shareCode['lookup_profile'])
            : null;

        $this->returnedProfile = isset($shareCode['returned_profile'])
            ? new ShareCodeMediaResponse($shareCode['returned_profile'])
            : null;

        $this->idPhoto = isset($shareCode['id_photo'])
            ? new ShareCodeMediaResponse($shareCode['id_photo'])
            : null;

        $this->file = isset($shareCode['file'])
            ? new ShareCodeMediaResponse($shareCode['file'])
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
     * @return ShareCodeMediaResponse|null
     */
    public function getLookupProfile(): ?ShareCodeMediaResponse
    {
        return $this->lookupProfile;
    }

    /**
     * @return ShareCodeMediaResponse|null
     */
    public function getReturnedProfile(): ?ShareCodeMediaResponse
    {
        return $this->returnedProfile;
    }

    /**
     * @return ShareCodeMediaResponse|null
     */
    public function getIdPhoto(): ?ShareCodeMediaResponse
    {
        return $this->idPhoto;
    }

    /**
     * @return ShareCodeMediaResponse|null
     */
    public function getFile(): ?ShareCodeMediaResponse
    {
        return $this->file;
    }

    /**
     * @return VerifyShareCodeTaskResponse[]
     */
    public function getVerifyShareCodeTasks(): array
    {
        return $this->filterTasksByType(VerifyShareCodeTaskResponse::class);
    }
}
