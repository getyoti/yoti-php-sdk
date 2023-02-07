<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Retrieve\Matching;

use Yoti\IDV\Constants;
use Yoti\IDV\Session\Retrieve\Contracts\CaMatchingStrategyResponse;

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
