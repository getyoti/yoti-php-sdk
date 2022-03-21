<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

use stdClass;
use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;

class RequestedFuzzyMatchingStrategy extends RequestedCaMatchingStrategy
{
    /**
     * @var float
     */
    private $fuzziness;

    /**
     * @param float $fuzziness
     */
    public function __construct(float $fuzziness)
    {
        $this->fuzziness = $fuzziness;
    }

    /**
     * @return float
     */
    public function getFuzziness(): float
    {
        return $this->fuzziness;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        $json = parent::jsonSerialize();
        $json->fuzziness = $this->getFuzziness();

        return $json;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Constants::FUZZY;
    }
}
