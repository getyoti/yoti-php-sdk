<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\Util\Validation;

class RequestedLivenessCheckBuilder
{

    private const ZOOM = 'ZOOM';

    /**
     * @var string
     */
    private $livenessType;

    /**
     * @var int
     */
    private $maxRetries = 1;

    public function forZoomLiveness(): self
    {
        return $this->forLivenessType(self::ZOOM);
    }

    public function forLivenessType(string $livenessType): self
    {
        $this->livenessType = $livenessType;
        return $this;
    }

    public function withMaxRetries(int $maxRetries): self
    {
        $this->maxRetries = $maxRetries;
        return $this;
    }

    public function build(): RequestedLivenessCheck
    {
        Validation::notEmptyString($this->livenessType, 'livenessType');
        Validation::notNull($this->maxRetries, 'maxRetries');

        $config = new RequestedLivenessConfig($this->livenessType, $this->maxRetries);
        return new RequestedLivenessCheck($config);
    }
}
