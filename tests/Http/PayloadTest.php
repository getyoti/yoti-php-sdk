<?php

namespace YotiTest\Http;

use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\Payload;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Payload
 */
class PayloadTest extends TestCase
{
    /**
     * @var string
     */
    public $payloadJSON;

    /**
     * @var string
     */
    public $base64Payload;

    /**
     * @var \Yoti\Http\Payload
     */
    public $payload;

    public function setup()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $this->payload = new Payload($amlProfile);

        // Expected test data
        $this->payloadJSON = '{"given_names":"Edward Richard George","family_name":"Heath",' .
            '"ssn":null,"address":{"post_code":null,"country":"GBR"}}';
        $this->base64Payload = 'eyJnaXZlbl9uYW1lcyI6IkVkd2FyZCBSaWNoYXJkIEdlb3JnZSIsImZhbWls' .
            'eV9uYW1lIjoiSGVhdGgiLCJzc24iOm51bGwsImFkZHJlc3MiOnsicG9zdF9jb2RlIjpudWxsLCJjb3VudHJ5IjoiR0JSIn19';
    }

    /**
     * Test getting Payload data as a JSON string.
     *
     * @covers ::getPayloadJSON
     */
    public function testGetPayloadJSON()
    {
        $this->assertEquals($this->payloadJSON, $this->payload->getPayloadJSON());
    }

    /**
     * Test getting Payload data as a Base64 string.
     *
     * @covers ::getBase64Payload
     */
    public function testGetBase64Payload()
    {
        $this->assertEquals($this->base64Payload, $this->payload->getBase64Payload());
    }

    /**
     * @covers ::getRawData
     */
    public function testGetRawData()
    {
        $this->assertEquals($this->payloadJSON, json_encode($this->payload->getRawData()));
    }

    /**
     * @covers ::__construct
     * @covers ::getPayloadJSON
     */
    public function testPassingAStringAsPayloadData()
    {
        $payloadData = 'payloadDataAsAString';
        $payload = new Payload($payloadData);
        $this->assertEquals(json_encode($payloadData), $payload->getPayloadJSON());
    }
}
