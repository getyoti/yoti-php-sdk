<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class ShareCodeResourceResponse extends ResourceResponse
{
    /**
     * @var string|null
     */
    private $createdAt;

    /**
     * @var string|null
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
     * @param array<string, mixed> $shareCode
     */
    public function __construct(array $shareCode)
    {
        parent::__construct($shareCode);

        $this->createdAt = $shareCode['created_at'] ?? null;
        $this->lastUpdated = $shareCode['last_updated'] ?? null;

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
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getLastUpdated(): ?string
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
