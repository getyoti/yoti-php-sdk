<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: SignedTimestamp.proto

namespace Yoti\Protobuf\Compubapi;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>compubapi_v1.SignedTimestamp</code>
 */
class SignedTimestamp extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int32 version = 1;</code>
     */
    private $version = 0;
    /**
     * Generated from protobuf field <code>uint64 timestamp = 2;</code>
     */
    private $timestamp = 0;
    /**
     * Generated from protobuf field <code>bytes message_digest = 3;</code>
     */
    private $message_digest = '';
    /**
     * Generated from protobuf field <code>bytes chain_digest = 4;</code>
     */
    private $chain_digest = '';
    /**
     * Generated from protobuf field <code>bytes chain_digest_skip1 = 5;</code>
     */
    private $chain_digest_skip1 = '';
    /**
     * Generated from protobuf field <code>bytes chain_digest_skip2 = 6;</code>
     */
    private $chain_digest_skip2 = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $version
     *     @type int|string $timestamp
     *     @type string $message_digest
     *     @type string $chain_digest
     *     @type string $chain_digest_skip1
     *     @type string $chain_digest_skip2
     * }
     */
    public function __construct($data = NULL) {
        \Yoti\Protobuf\Compubapi\GPBMetadata\SignedTimestamp::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int32 version = 1;</code>
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Generated from protobuf field <code>int32 version = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setVersion($var)
    {
        GPBUtil::checkInt32($var);
        $this->version = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 timestamp = 2;</code>
     * @return int|string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Generated from protobuf field <code>uint64 timestamp = 2;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTimestamp($var)
    {
        GPBUtil::checkUint64($var);
        $this->timestamp = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes message_digest = 3;</code>
     * @return string
     */
    public function getMessageDigest()
    {
        return $this->message_digest;
    }

    /**
     * Generated from protobuf field <code>bytes message_digest = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setMessageDigest($var)
    {
        GPBUtil::checkString($var, False);
        $this->message_digest = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes chain_digest = 4;</code>
     * @return string
     */
    public function getChainDigest()
    {
        return $this->chain_digest;
    }

    /**
     * Generated from protobuf field <code>bytes chain_digest = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setChainDigest($var)
    {
        GPBUtil::checkString($var, False);
        $this->chain_digest = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes chain_digest_skip1 = 5;</code>
     * @return string
     */
    public function getChainDigestSkip1()
    {
        return $this->chain_digest_skip1;
    }

    /**
     * Generated from protobuf field <code>bytes chain_digest_skip1 = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setChainDigestSkip1($var)
    {
        GPBUtil::checkString($var, False);
        $this->chain_digest_skip1 = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes chain_digest_skip2 = 6;</code>
     * @return string
     */
    public function getChainDigestSkip2()
    {
        return $this->chain_digest_skip2;
    }

    /**
     * Generated from protobuf field <code>bytes chain_digest_skip2 = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setChainDigestSkip2($var)
    {
        GPBUtil::checkString($var, False);
        $this->chain_digest_skip2 = $var;

        return $this;
    }

}
