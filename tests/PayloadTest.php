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
        $country = new Country('AUT');
        $amlAddress = new AmlAddress($country);
        $amlProfile = new AmlProfile('Andreas', 'Brandstaetter', $amlAddress);
        $this->payload = new Payload($amlProfile->getData());

        // Expected test data
        $this->expectedJSON = '{"given_names":"Andreas","family_name":"Brandstaetter","ssn":null,"address":{"post_code":null,"country":"AUT"}}';
        $this->expectedBase64Payload = 'eyJnaXZlbl9uYW1lcyI6IkFuZHJlYXMiLCJmYW1pbHlfbmFtZSI6IkJyYW5kc3RhZXR0ZXIiLCJzc24iOm51bGwsImFkZHJlc3MiOnsicG9zdF9jb2RlIjpudWxsLCJjb3VudHJ5IjoiQVVUIn19';
    }

    /**
     * Test getting Payload JSON.
     */
    public function testPayloadJSON()
    {
        $this->assertEquals($this->expectedJSON, $this->payload->getPayloadJSON());
    }

    /**
     * Test getting Base64 Payload.
     */
    public function testBase64Payload()
    {
        $this->assertEquals($this->expectedBase64Payload, $this->payload->getBase64Payload());
    }
}