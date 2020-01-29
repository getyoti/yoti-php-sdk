<?php

declare(strict_types=1);

namespace Yoti\Test\Profile;

use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\ExtraData;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Profile\Receipt;
use Yoti\Profile\UserProfile;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Profile\Receipt
 */
class ReceiptTest extends TestCase
{
    /**
     * @var \Yoti\Util\PemFile
     */
    public $pemFile;

    /**
     * @var array Receipt array.
     */
    private $receiptArr;

    /**
     * @var Receipt
     */
    public $receipt;

    public function setup(): void
    {
        $this->pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $this->receiptArr = json_decode(file_get_contents(TestData::RECEIPT_JSON), true)['receipt'];
        $this->receipt = new Receipt($this->receiptArr);
    }

    /**
     * @covers ::__construct
     * @covers ::validateReceipt
     */
    public function testShouldThrowExceptionForInvalidReceipt()
    {
        $this->expectException(\Yoti\Exception\ReceiptException::class);

        new Receipt([]);
    }

    /**
     * @covers ::getTimestamp
     */
    public function testGetTimestamp()
    {
        $this->assertEquals('2016-07-19T08:55:38Z', $this->receipt->getTimestamp());
    }

    /**
     * @covers ::getAttribute
     */
    public function testGetAttribute()
    {
        $someKey = 'some key';
        $someValue = 'some value';

        $receipt = new Receipt([
            'wrapped_receipt_key' => '',
            $someKey => $someValue,
        ]);

        $this->assertEquals($someValue, $receipt->getAttribute($someKey));
    }

    /**
     * @covers ::getAttribute
     */
    public function testGetAttributeNull()
    {
        $receipt = new Receipt([
            'wrapped_receipt_key' => '',
        ]);

        $this->assertNull($receipt->getAttribute('some key'));
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
     * @covers ::getParentRememberMeId
     */
    public function testGetParentRememberMeId()
    {
        $parentRememberMeId = 'f5RjVQMyoKOvO/hkv43Ik+t6d6mGfP2tdrNijH4k4qafTG0FSNUgQIvd2Z3Nx1j8';
        $this->assertEquals($parentRememberMeId, $this->receipt->getParentRememberMeId());
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
     * @covers ::parseOtherPartyProfileContent
     * @covers ::parseAttribute
     * @covers ::getWrappedReceiptKey
     * @covers ::decryptAttribute
     */
    public function testShouldParseOtherPartyProfileContent()
    {
        $protobufAttributesList = $this->receipt->parseOtherPartyProfileContent($this->pemFile);

        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesList(
            $protobufAttributesList
        );
        $profile = new UserProfile($yotiAttributesList);
        $this->assertEquals('+447474747474', $profile->getPhoneNumber()->getValue());
    }

    /**
     * @covers ::parseProfileContent
     * @covers ::parseAttribute
     * @covers ::decryptAttribute
     */
    public function testShouldParseProfileContent()
    {
        $protobufAttributesList = $this->receipt->parseProfileContent($this->pemFile);

        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesList(
            $protobufAttributesList
        );
        $applicationProfile = new ApplicationProfile($yotiAttributesList);

        $this->assertEquals('', $applicationProfile->getApplicationLogo());
        $this->assertEquals('https://example.com', $applicationProfile->getApplicationUrl()->getValue());
        $this->assertEquals('Node SDK Test', $applicationProfile->getApplicationName()->getValue());
        $this->assertEquals('#ffffff', $applicationProfile->getApplicationReceiptBgColor()->getValue());
    }

    /**
     * @covers ::parseExtraData
     * @covers ::decryptAttribute
     */
    public function testParseExtraData()
    {
        $extraData = $this->receipt->parseExtraData(PemFile::fromFilePath(TestData::PEM_FILE));

        $this->assertInstanceOf(ExtraData::class, $extraData);
        $this->assertInstanceOf(AttributeIssuanceDetails::class, $extraData->getAttributeIssuanceDetails());
    }

    /**
     * @covers ::parseAttribute
     * @covers ::decryptAttribute
     */
    public function testParseAttributeNull()
    {
        $someReceiptAttribute = $this->receipt->parseAttribute(
            'some-unknown-property',
            PemFile::fromFilePath(TestData::PEM_FILE)
        );

        $this->assertInstanceOf(AttributeList::class, $someReceiptAttribute);
    }
}
