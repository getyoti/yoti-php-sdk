<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Orthogonal;

use Yoti\Util\Validation;

class TypeRestriction implements \JsonSerializable
{

    /**
     * @var string
     */
    private $inclusion;

    /**
     * @var string[]
     */
    private $documentTypes;

    /**
     * @param string $inclusion
     * @param string[] $documentTypes
     */
    public function __construct(string $inclusion, array $documentTypes)
    {
        $this->inclusion = $inclusion;

        Validation::isArrayOfStrings($documentTypes, 'documentTypes');
        $this->documentTypes = $documentTypes;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'inclusion' => $this->inclusion,
            'document_types' => $this->documentTypes,
        ];
    }
}
