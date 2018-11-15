<?php
namespace Yoti\Entity;

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
     * @param string $value
     *
     * @param array $anchorsMap
     */
    public function __construct($name, $value, array $anchorsMap)
    {
        $this->name = $name;
        $this->value = $value;

        $this->setSources($anchorsMap);
        $this->setVerifiers($anchorsMap);
        $this->setAnchors($anchorsMap);
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
     * Return an array of anchors e.g
     * [
     *  new Anchor(),
     *  new Anchor(),
     *  ...
     * ]
     *
     * @return array
     */
    public function getAnchors()
    {
        return $this->anchors;
    }

    private function setSources(array $anchorsMap)
    {
        $this->sources = $this->getAnchorType(
            $anchorsMap,
            Anchor::TYPE_SOURCE_OID
        );
    }

    private function setVerifiers(array $anchorsMap)
    {
        $this->verifiers = $this->getAnchorType(
            $anchorsMap,
            Anchor::TYPE_VERIFIER_OID
        );
    }

    private function setAnchors(array $anchorsMap)
    {
        // Remove Oids from the anchorsMap
        $anchors = [];
        array_walk($anchorsMap, function($val) use(&$anchors) {
            $anchors = array_merge($anchors, array_values($val));
        });
        $this->anchors = $anchors;
    }

    /**
     * @param string $anchorType
     * @return array
     */
    private function getAnchorType($anchorsMap, $anchorType)
    {
        return isset($anchorsMap[$anchorType]) ? $anchorsMap[$anchorType] : [];
    }
}