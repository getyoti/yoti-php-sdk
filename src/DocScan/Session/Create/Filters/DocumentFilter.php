<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use Yoti\Util\Validation;

abstract class DocumentFilter implements \JsonSerializable
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var bool|null
     */
    private $allowDigitalIds;

    /**
     * @var Provider[]|null
     */
    private $allowedProviders;

    /**
     * @param string $type
     * @param bool|null $allowDigitalIds
     * @param Provider[]|null $allowedProviders
     */
    public function __construct(string $type, ?bool $allowDigitalIds = null, ?array $allowedProviders = null)
    {
        $this->type = $type;
        $this->allowDigitalIds = $allowDigitalIds;

        if (isset($allowedProviders)) {
            Validation::isArrayOfType($allowedProviders, [Provider::class], 'allowedProviders');
            // Re-index so non-sequential keys (e.g. from array_filter) still serialise as a JSON array.
            $allowedProviders = array_values($allowedProviders);
        }
        $this->allowedProviders = $allowedProviders;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = (object) [
            'type' => $this->type,
        ];

        if (isset($this->allowDigitalIds)) {
            $jsonData->allow_digital_ids = $this->allowDigitalIds;
        }

        if (isset($this->allowedProviders)) {
            $jsonData->allowed_providers = $this->allowedProviders;
        }

        return $jsonData;
    }

    /**
     * @return bool|null
     */
    public function isAllowDigitalIds(): ?bool
    {
        return $this->allowDigitalIds;
    }

    /**
     * @return Provider[]|null
     */
    public function getAllowedProviders(): ?array
    {
        return $this->allowedProviders;
    }
}
