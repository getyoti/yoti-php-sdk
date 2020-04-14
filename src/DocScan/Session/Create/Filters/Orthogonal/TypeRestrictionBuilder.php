<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Orthogonal;

use Yoti\DocScan\Constants;

class TypeRestrictionBuilder
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
     * @return self
     */
    public function forWhitelist(): self
    {
        $this->inclusion = Constants::INCLUSION_WHITELIST;
        return $this;
    }

    /**
     * @return self
     */
    public function forBlacklist(): self
    {
        $this->inclusion = Constants::INCLUSION_BLACKLIST;
        return $this;
    }

    /**
     * @return self
     */
    public function withDocumentRestriction(string $documentType): self
    {
        $this->documentTypes[] = $documentType;
        return $this;
    }

    /**
     * @return TypeRestriction
     */
    public function build(): TypeRestriction
    {
        return new TypeRestriction($this->inclusion, $this->documentTypes);
    }
}
