<?php

namespace Yoti\Identity;

class ShareSessionFetched implements \JsonSerializable
{
    private string $id;

    private string $status;

    private string $created;

    private string $updated;

    private string $expiry;

    private string $qrCodeId;

    private string $receiptId;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        if (isset($sessionData['id'])) {
            $this->id = $sessionData['id'];
        }
        if (isset($sessionData['status'])) {
            $this->status = $sessionData['status'];
        }
        if (isset($sessionData['expiry'])) {
            $this->expiry = $sessionData['expiry'];
        }
        if (isset($sessionData['created'])) {
            $this->created = $sessionData['created'];
        }
        if (isset($sessionData['updated'])) {
            $this->updated = $sessionData['updated'];
        }
        if (isset($sessionData['qrCode'])) {
            $this->qrCodeId = $sessionData['qrCode']['id'];
        }
        if (isset($sessionData['receipt'])) {
            $this->receiptId = $sessionData['receipt']['id'];
        }
    }

    public function jsonSerialize(): object
    {
        return (object)[
            'id' => $this->id,
            'status' => $this->status,
            'expiry' => $this->expiry,
            'created' => $this->created,
            'updated' => $this->updated,
            'qrCodeId' => $this->qrCodeId,
            'receiptId' => $this->receiptId,
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getExpiry(): string
    {
        return $this->expiry;
    }

    /**
     * @return string
     */
    public function getQrCodeId(): string
    {
        return $this->qrCodeId;
    }

    /**
     * @return string
     */
    public function getReceiptId(): string
    {
        return $this->receiptId;
    }
}
