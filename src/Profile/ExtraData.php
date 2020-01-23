<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
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
            function ($dataEntry): bool {
                return $dataEntry instanceof AttributeIssuanceDetails;
            }
        );

        $firstItem = reset($attributeIssuanceDetailsList);
        $this->attributeIssuanceDetails = $firstItem === false ? null : $firstItem;
    }

    /**
     * @return \Yoti\Profile\ExtraData\AttributeIssuanceDetails|null
     */
    public function getAttributeIssuanceDetails(): ?AttributeIssuanceDetails
    {
        return $this->attributeIssuanceDetails;
    }
}
