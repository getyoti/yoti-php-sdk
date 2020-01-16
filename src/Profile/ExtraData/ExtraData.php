<?php

declare(strict_types=1);

namespace Yoti\Profile\ExtraData;

use Yoti\Util\Validation;

class ExtraData
{
    /**
     * @var \Yoti\Profile\ExtraData\AttributeIssuanceDetails|null
     */
    private $attributeIssuanceDetails;

    /**
     * @param mixed[] $dataEntryList
     */
    public function __construct(array $dataEntryList)
    {
        Validation::isArrayOfType($dataEntryList, [AttributeIssuanceDetails::class], 'dataEntryList');
        $this->setAttributeIssuanceDetails($dataEntryList);
    }

    /**
     * @param mixed[] $dataEntryList
     */
    private function setAttributeIssuanceDetails(array $dataEntryList): void
    {
        $attributeIssuanceDetailsList = array_filter(
            $dataEntryList,
            function ($dataEntry) {
                return $dataEntry instanceof AttributeIssuanceDetails;
            }
        );

        $this->attributeIssuanceDetails = reset($attributeIssuanceDetailsList) ?: null;
    }

    /**
     * @return \Yoti\Profile\ExtraData\AttributeIssuanceDetails|null
     */
    public function getAttributeIssuanceDetails(): ?AttributeIssuanceDetails
    {
        return $this->attributeIssuanceDetails;
    }
}
