<?php
namespace Yoti\Entity;

use Yoti\Util\Profile\AnchorProcessor;

class Attribute
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private $sources;

    /**
     * @var array
     */
    private $verifiers;

    /**
     * @var array
     */
    private $anchors;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param mixed $value
     * @param array $sources
     * @param array $verifiers
     */
    public function __construct($name, $value, array $anchors = NULL)
    {
        $this->name = $name;
        $this->value = $value;
        $this->anchors = $anchors;

        $this->setSources();
        $this->setVerifiers();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @return array
     */
    public function getVerifiers()
    {
        return $this->verifiers;
    }

    /**
     * @return array
     */
    public function getAnchors()
    {
        return $this->anchors;
    }

    private function setSources()
    {
        $this->sources = isset($this->anchors[Anchor::TYPE_SOURCES_OID]) ?
            $this->anchors[Anchor::TYPE_SOURCES_OID] : [];
    }

    private function setVerifiers()
    {
        $this->verifiers = isset($this->anchors[Anchor::TYPE_VERIFIERS_OID]) ?
            $this->anchors[Anchor::TYPE_VERIFIERS_OID] : [];
    }
}