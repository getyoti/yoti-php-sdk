<?php
namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use Yoti\Entity\Receipt;
use Yoti\Entity\ApplicationProfile;
use Yoti\Util\Profile\AttributeConverter;

class ReceiptTest extends TestCase
{
    public $pem;

    /**
     * @var Receipt
     */
    public $receipt;

    public function setup()
    {
        $this->pem = file_get_contents(PEM_FILE);
        $receiptArr = json_decode(file_get_contents(RECEIPT_JSON), true);
        $this->receipt = new Receipt($receiptArr['receipt']);
    }

    public function testShouldThrowExceptionForInvalidReceipt()
    {
        $this->expectException('\Yoti\Exception\ReceiptException');
        $receipt = new Receipt([]);
    }

    public function testGetTimestamp()
    {
        $this->assertEquals('2016-07-19T08:55:38Z', $this->receipt->getTimestamp());
    }

    public function testGetRememberMeId()
    {
        $expectedRememberMeId = 'Hig2yAT79cWvseSuXcIuCLa5lNkAPy70rxetUaeHlTJGmiwc/g1MWdYWYrexWvPU';
        $this->assertEquals($expectedRememberMeId, $this->receipt->getRememberMeId());
    }

    public function testGetSharingOutcome()
    {
        $this->assertEquals('SUCCESS', $this->receipt->getSharingOutcome());
    }

    public function testGetReceiptId()
    {
        $expectedReceiptId = '9HNJDX5bEIN5TqBm0OGzVIc1LaAmbzfx6eIrwNdwpHvKeQmgPujyogC+r7hJCVPl';
        $this->assertEquals(
            $expectedReceiptId,
            $this->receipt->getReceiptId()
        );
    }

    public function testShouldParseOtherPartyProfileContent()
    {
        $protobufAttributesList = $this->receipt->parseAttribute(
            Receipt::ATTR_OTHER_PARTY_PROFILE_CONTENT,
            $this->pem
        );
        $yotiAttributesList = AttributeConverter::convertToYotiAttributesMap(
            $protobufAttributesList
        );
        $profile = new Profile($yotiAttributesList);
        $this->assertEquals('+447474747474', $profile->getPhoneNumber()->getValue());
    }

    public function testShouldParseProfileContent()
    {
        $protobufAttributesList = $this->receipt->parseAttribute(
            Receipt::ATTR_PROFILE_CONTENT,
            $this->pem
        );
        $yotiAttributesList = AttributeConverter::convertToYotiAttributesMap(
            $protobufAttributesList
        );
        $applicationProfile = new ApplicationProfile($yotiAttributesList);

        $this->assertEquals('', $applicationProfile->getApplicationLogo());
        $this->assertEquals('https://example.com', $applicationProfile->getApplicationUrl()->getValue());
        $this->assertEquals('Node SDK Test', $applicationProfile->getApplicationName()->getValue());
        $this->assertEquals('#ffffff', $applicationProfile->getApplicationReceiptBgColor()->getValue());
    }
}