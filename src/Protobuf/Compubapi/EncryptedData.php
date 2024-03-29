<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: EncryptedData.proto

namespace Yoti\Protobuf\Compubapi;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>compubapi_v1.EncryptedData</code>
 */
class EncryptedData extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>bytes iv = 1;</code>
     */
    protected $iv = '';
    /**
     * Generated from protobuf field <code>bytes cipher_text = 2;</code>
     */
    protected $cipher_text = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $iv
     *     @type string $cipher_text
     * }
     */
    public function __construct($data = NULL) {
        \Yoti\Protobuf\Compubapi\GPBMetadata\EncryptedData::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>bytes iv = 1;</code>
     * @return string
     */
    public function getIv()
    {
        return $this->iv;
    }

    /**
     * Generated from protobuf field <code>bytes iv = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setIv($var)
    {
        GPBUtil::checkString($var, False);
        $this->iv = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes cipher_text = 2;</code>
     * @return string
     */
    public function getCipherText()
    {
        return $this->cipher_text;
    }

    /**
     * Generated from protobuf field <code>bytes cipher_text = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setCipherText($var)
    {
        GPBUtil::checkString($var, False);
        $this->cipher_text = $var;

        return $this;
    }

}

