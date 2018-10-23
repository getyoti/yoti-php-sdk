<?php
namespace Yoti\Entity;

class AgeVerification
{
    /**
     * @var int
     */
    private $age;

    /**
     * @var boolean
     */
    private $result;

    /**
     * @var string
     */
    private $checkType;

    /**
     * @var Attribute
     */
    private $derivedAttribute;

    public function __construct(Attribute $derivedAttribute, $checkType, $age, $result)
    {
        $this->age = $age;
        $this->result = $result;
        $this->checkType = $checkType;
        $this->derivedAttribute = $derivedAttribute;
    }

    /**
     * The type of age check performed, as specified on dashboard.
     * Currently this might be 'age_over' or 'age_under'.
     *
     * @return string $checkType
     */
    public function getCheckType()
    {
        return $this->checkType;
    }

    /**
     * The age that was that checked, as specified on dashboard.
     *
     * @return int $age
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Whether or not the profile passed the age check.
     *
     * @return boolean $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * The wrapped profile attribute. Use this if you need access to the underlying List of {@link Anchor}s
     *
     * @return \Yoti\Entity\Attribute
     */
    public function getAttribute()
    {
        return $this->derivedAttribute;
    }
}