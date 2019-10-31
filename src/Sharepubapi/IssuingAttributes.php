<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: IssuingAttributes.proto

namespace Yoti\Sharepubapi;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>sharepubapi_v1.IssuingAttributes</code>
 */
class IssuingAttributes extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string expiry_date = 1;</code>
     */
    private $expiry_date = '';
    /**
     * Generated from protobuf field <code>repeated .sharepubapi_v1.Definition definitions = 2;</code>
     */
    private $definitions;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $expiry_date
     *     @type \Yoti\Sharepubapi\Definition[]|\Google\Protobuf\Internal\RepeatedField $definitions
     * }
     */
    public function __construct($data = NULL) {
        \Yoti\Sharepubapi\GPBMetadata\IssuingAttributes::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string expiry_date = 1;</code>
     * @return string
     */
    public function getExpiryDate()
    {
        return $this->expiry_date;
    }

    /**
     * Generated from protobuf field <code>string expiry_date = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setExpiryDate($var)
    {
        GPBUtil::checkString($var, True);
        $this->expiry_date = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .sharepubapi_v1.Definition definitions = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Generated from protobuf field <code>repeated .sharepubapi_v1.Definition definitions = 2;</code>
     * @param \Yoti\Sharepubapi\Definition[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setDefinitions($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Yoti\Sharepubapi\Definition::class);
        $this->definitions = $arr;

        return $this;
    }

}
