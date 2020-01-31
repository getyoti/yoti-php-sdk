<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request\Attribute;

use Yoti\Profile\Attribute\DocumentDetails;

class SandboxDocumentDetails extends DocumentDetails
{
    public function getValue(): string
    {
        $value = $this->getType() . ' ' . $this->getIssuingCountry() . ' ' . $this->getDocumentNumber() . ' ';

        $expirationDate = $this->getExpirationDate();
        $value .= (null !== $expirationDate) ? $expirationDate->format('Y-m-d') : '-';
        $value .= (null !== $this->getIssuingAuthority()) ? ' ' . $this->getIssuingAuthority() : '';

        return $value;
    }
}
