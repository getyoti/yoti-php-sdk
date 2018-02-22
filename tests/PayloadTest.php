<?php

use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\Payload;

class PayloadTest extends PHPUnit\Framework\TestCase
{
    public $expectedJSON;
    public $expectedBase64Payload;
    public $payload;

    public function setup()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $this->payload = new Payload($amlProfile->getData());

        // Expected test data
        $this->expectedJSON = '{"given_names":"Edward Richard George","family_name":"Heath","ssn":null,"address":{"post_code":null,"country":"GBR"}}';
        $this->expectedBase64Payload = 'eyJnaXZlbl9uYW1lcyI6IkVkd2FyZCBSaWNoYXJkIEdlb3JnZSIsImZhbWlseV9uYW1lIjoiSGVhdGgiLCJzc24iOm51bGwsImFkZHJlc3MiOnsicG9zdF9jb2RlIjpudWxsLCJjb3VudHJ5IjoiR0JSIn19';
    }

    /**
     * Test getting Payload JSON.
     */
    public function testGetPayloadJSON()
    {
        $this->assertEquals($this->expectedJSON, $this->payload->getPayloadJSON());
    }

    /**
     * Test getting Base64 Payload.
     */
    public function testGetBase64Payload()
    {
        $this->assertEquals($this->expectedBase64Payload, $this->payload->getBase64Payload());
    }

    public function testGetRawData()
    {
        $this->assertEquals($this->expectedJSON, json_encode($this->payload->getRawData()));
    }
}