<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use stdClass;
use Yoti\Util\Json;

class CompanyProfile implements JsonSerializable
{
    /**
     * @var string|null
     */
    private $companyName;

    /**
     * @param string|null $companyName
     */
    public function __construct(?string $companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) Json::withoutNullValues([
            'company_name' => $this->companyName,
        ]);
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
}
