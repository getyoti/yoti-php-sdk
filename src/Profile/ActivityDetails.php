<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Psr\Log\LoggerInterface;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Util\DateTime;
use Yoti\Util\Logger;
use Yoti\Util\PemFile;

/**
 * Class ActivityDetails
 *
 * @package Yoti
 * @author Yoti SDK <websdk@yoti.com>
 */
class ActivityDetails
{
    /**
     * @var string|null receipt identifier
     */
    private $rememberMeId;

    /**
     * @var string|null parent receipt identifier
     */
    private $parentRememberMeId;

    /**
     * @var \Yoti\Profile\UserProfile
     */
    private $userProfile;

    /**
     * @var \DateTime|null
     */
    private $timestamp;

    /**
     * @var ApplicationProfile
     */
    private $applicationProfile;

    /**
     * @var \Yoti\Profile\Receipt
     */
    private $receipt;

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var \Yoti\Profile\ExtraData
     */
    private $extraData;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * ActivityDetails constructor.
     *
     * @param \Yoti\Profile\Receipt $receipt
     * @param \Yoti\Util\PemFile $pemFile
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(Receipt $receipt, PemFile $pemFile, ?LoggerInterface $logger = null)
    {
        $this->receipt = $receipt;
        $this->pemFile = $pemFile;
        $this->logger = $logger ?? new Logger();

        $this->setProfile();
        $this->setTimestamp();
        $this->setRememberMeId();
        $this->setParentRememberMeId();
        $this->setApplicationProfile();
        $this->setExtraData();
    }

    private function setRememberMeId(): void
    {
        $this->rememberMeId = $this->receipt->getRememberMeId();
    }

    private function setParentRememberMeId(): void
    {
        $this->parentRememberMeId = $this->receipt->getParentRememberMeId();
    }

    private function setTimestamp(): void
    {
        try {
            $timestamp = $this->receipt->getTimestamp();
            $this->timestamp = DateTime::stringToDateTime($timestamp);
        } catch (\Exception $e) {
            $this->timestamp = null;
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
        }
    }

    private function setProfile(): void
    {
        $protobufAttrList = $this->receipt->parseOtherPartyProfileContent($this->pemFile);

        $this->userProfile = new UserProfile(
            AttributeListConverter::convertToYotiAttributesList($protobufAttrList, $this->logger)
        );
    }

    private function setApplicationProfile(): void
    {
        $protobufAttrList = $this->receipt->parseProfileContent($this->pemFile);

        $this->applicationProfile = new ApplicationProfile(
            AttributeListConverter::convertToYotiAttributesList($protobufAttrList, $this->logger)
        );
    }

    /**
     * @return string|null
     */
    public function getReceiptId(): ?string
    {
        return $this->receipt->getReceiptId();
    }

    /**
     * @return \DateTime|null
     */
    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return ApplicationProfile
     */
    public function getApplicationProfile(): ApplicationProfile
    {
        return $this->applicationProfile;
    }

    /**
     * Get user profile object.
     *
     * @return UserProfile
     */
    public function getProfile(): UserProfile
    {
        return $this->userProfile;
    }

    /**
     * Get rememberMeId.
     *
     * @return null|string
     */
    public function getRememberMeId(): ?string
    {
        return $this->rememberMeId;
    }

    /**
     * Get Parent Remember Me Id.
     *
     * @return null|string
     */
    public function getParentRememberMeId(): ?string
    {
        return $this->parentRememberMeId;
    }

    /**
     * Set extra data from receipt.
     */
    private function setExtraData(): void
    {
        $this->extraData = $this->receipt->parseExtraData($this->pemFile);
    }

    /**
     * @return \Yoti\Profile\ExtraData
     */
    public function getExtraData(): ExtraData
    {
        return $this->extraData;
    }
}
