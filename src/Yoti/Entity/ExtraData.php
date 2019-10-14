<?php

namespace Yoti\Entity;

use Yoti\Util\Validation;

class ExtraData
{
    /**
     * @var \Yoti\Entity\DataEntry[]
     */
    private $dataEntryList = [];

    /**
     * @param mixed[] $dataEntryList
     */
    public function __construct(array $dataEntryList)
    {
        Validation::isArrayOfType($dataEntryList, [CredentialIssuanceDetails::class], 'dataEntryList');
        $this->dataEntryList = $dataEntryList;
    }

    /**
     * @return \Yoti\Entity\DataEntry|null
     */
    public function getCredentialIssuanceDetails()
    {
        $credentialIssuanceDetails = array_filter(
            $this->dataEntryList,
            function ($dataEntry) {
                return $dataEntry instanceof CredentialIssuanceDetails;
            }
        );

        return reset($credentialIssuanceDetails) ?: null;
    }
}
