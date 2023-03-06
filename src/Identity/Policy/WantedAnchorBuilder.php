<?php

namespace Yoti\Identity\Policy;

use Yoti\Util\Validation;

class WantedAnchorBuilder
{
    private string $value;

    private string $subType = '';

    public function withValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function withSubType(string $subType): self
    {
        $this->subType = $subType;
        return $this;
    }

    public function build(): WantedAnchor
    {
        Validation::notNull($this->value, 'value');
        Validation::notNull($this->subType, 'sub_type');

        return new WantedAnchor($this->value, $this->subType);
    }
}
