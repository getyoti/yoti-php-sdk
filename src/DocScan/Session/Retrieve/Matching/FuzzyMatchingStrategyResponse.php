<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Matching;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Contracts\CaMatchingStrategyResponse;

class FuzzyMatchingStrategyResponse extends CaMatchingStrategyResponse
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
        $this->type = Constants::FUZZY;
        $this->fuzziness = $fuzziness;
    }

    /**
     * @return float
     */
    public function getFuzziness(): float
    {
        return $this->fuzziness;
    }
}
