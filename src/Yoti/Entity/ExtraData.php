<?php

namespace Yoti\Entity;

use Yoti\Util\Validation;

class ExtraData
{
    /**
     * @var \Yoti\Entity\AttributeIssuanceDetails|null
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
    private function setAttributeIssuanceDetails($dataEntryList)
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
     * @return \Yoti\Entity\AttributeIssuanceDetails|null
     */
    public function getAttributeIssuanceDetails()
    {
        return $this->attributeIssuanceDetails;
    }
}
