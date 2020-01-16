<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Json;
use Yoti\Util\Validation;

/**
 * List of constraints to apply to a wanted attribute.
 */
class Constraints implements \JsonSerializable
{
    /**
     * @var \JsonSerializable[]
     */
    private $constraints = [];

    /**
     * @param \JsonSerializable[] $constraints
     */
    public function __construct(array $constraints = [])
    {
        Validation::isArrayOfType($constraints, [SourceConstraint::class], 'constraints');
        $this->constraints = $constraints;
    }

    /**
     * @inheritDoc
     *
     * @return \JsonSerializable[]
     */
    public function jsonSerialize(): array
    {
        return $this->constraints;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
