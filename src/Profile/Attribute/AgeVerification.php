<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

use Yoti\Profile\Attribute;
use Yoti\Util\Validation;

class AgeVerification
{
    /**
     * @var int
     */
    private $age;

    /**
     * @var bool
     */
    private $result;

    /**
     * @var string
     */
    private $checkType;

    /**
     * @var Attribute
     */
    private $attribute;

    public function __construct(Attribute $attribute)
    {
        Validation::matchesPattern($attribute->getName(), '/^[^:]+:(?!.*:)[0-9]+$/', 'attribute.name');
        $ageCheckArr = explode(':', $attribute->getName());

        $this->attribute = $attribute;
        $this->result = $attribute->getValue() === 'true';
        $this->checkType = $ageCheckArr[0];
        $this->age = (int) $ageCheckArr[1];
    }

    /**
     * The type of age check performed, as specified on Yoti Hub.
     * Currently this might be 'age_over' or 'age_under'.
     *
     * @return string $checkType
     */
    public function getCheckType(): string
    {
        return $this->checkType;
    }

    /**
     * The age that was that checked, as specified on Yoti Hub.
     *
     * @return int $age
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * Whether or not the profile passed the age check.
     *
     * @return bool $result
     */
    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * The wrapped profile attribute. Use this if you need access to the underlying List of {@link Anchor}s
     *
     * @return \Yoti\Profile\Attribute
     */
    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }
}
