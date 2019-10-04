<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use Yoti\Entity\Receipt;
use Yoti\Entity\ApplicationProfile;
use Yoti\Util\Profile\AttributeListConverter;

/**
 * @coversDefaultClass \Yoti\Entity\Receipt
 */
class ReceiptTest extends TestCase
{
    /**
     * @var string Pem file contents.
     */
    public $pem;

    /**
     * @var array Receipt array.
     */
    private $receiptArr;

    /**
     * @var Receipt
     */
    public $receipt;

    public function setup()
    {
        $this->pem = file_get_contents(PEM_FILE);
        $this->receiptArr = json_decode(file_get_contents(RECEIPT_JSON), true)['receipt'];
        $this->receipt = new Receipt($this->receiptArr);
    }

    /**
     * @covers ::__construct
     */
    public function testShouldThrowExceptionForInvalidReceipt()
    {
        $this->expectException('\Yoti\Exception\ReceiptException');
        $receipt = new Receipt([]);
    }

    /**
     * @covers ::getTimestamp
     */
    public function testGetTimestamp()
    {
        $this->assertEquals('2016-07-19T08:55:38Z', $this->receipt->getTimestamp());
    }

    /**
     * @covers ::getRememberMeId
     */
    public function testGetRememberMeId()
    {
        $expectedRememberMeId = 'Hig2yAT79cWvseSuXcIuCLa5lNkAPy70rxetUaeHlTJGmiwc/g1MWdYWYrexWvPU';
        $this->assertEquals($expectedRememberMeId, $this->receipt->getRememberMeId());
    }

    /**
     * @covers ::getRememberMeId
     */
    public function testGetRememberMeIdNotPresent()
    {
        unset($this->receiptArr['remember_me_id']);
        $receipt = new Receipt($this->receiptArr);
        $this->assertNull($receipt->getRememberMeId());
    }

    /**
     * @covers ::getRememberMeId
     */
    public function testGetRememberMeIdEmpty()
    {
        $this->receiptArr['remember_me_id'] = '';
        $receipt = new Receipt($this->receiptArr);
        $this->assertEquals('', $receipt->getRememberMeId());
    }

    /**
     * @covers ::getSharingOutcome
     */
    public function testGetSharingOutcome()
    {
        $this->assertEquals('SUCCESS', $this->receipt->getSharingOutcome());
    }

    /**
     * @covers ::getReceiptId
     */
    public function testGetReceiptId()
    {
        $expectedReceiptId = '9HNJDX5bEIN5TqBm0OGzVIc1LaAmbzfx6eIrwNdwpHvKeQmgPujyogC+r7hJCVPl';
        $this->assertEquals(
            $expectedReceiptId,
            $this->receipt->getReceiptId()
        );
    }

    /**
     * @covers ::parseAttribute
     */
    public function testShouldParseOtherPartyProfileContent()
    {
        $protobufAttributesList = $this->receipt->parseAttribute(
            Receipt::ATTR_OTHER_PARTY_PROFILE_CONTENT,
            $this->pem
        );
        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesMap(
            $protobufAttributesList
        );
        $profile = new Profile($yotiAttributesList);
        $this->assertEquals('+447474747474', $profile->getPhoneNumber()->getValue());
    }

    /**
     * @covers ::parseAttribute
     */
    public function testShouldParseProfileContent()
    {
        $protobufAttributesList = $this->receipt->parseAttribute(
            Receipt::ATTR_PROFILE_CONTENT,
            $this->pem
        );
        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesMap(
            $protobufAttributesList
        );
        $applicationProfile = new ApplicationProfile($yotiAttributesList);

        $this->assertEquals('', $applicationProfile->getApplicationLogo());
        $this->assertEquals('https://example.com', $applicationProfile->getApplicationUrl()->getValue());
        $this->assertEquals('Node SDK Test', $applicationProfile->getApplicationName()->getValue());
        $this->assertEquals('#ffffff', $applicationProfile->getApplicationReceiptBgColor()->getValue());
    }
}
