<?php

namespace YotiTest\Profile\Attribute;

use Yoti\Profile\Attribute\DocumentDetails;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Attribute\DocumentDetails
 */
class DocumentDetailsTest extends TestCase
{
    /**
     * @var DocumentDetails
     */
    public $dummyDocumentDetails;

    public function setup(): void
    {
        $dummyValue = 'PASSPORT GBR 01234567 2020-01-01';
        $this->dummyDocumentDetails = new DocumentDetails($dummyValue);
    }

    /**
     * @covers ::__construct
     * @covers ::parseFromValue
     * @covers ::setType
     * @covers ::setIssuingCountry
     * @covers ::setDocumentNumber
     * @covers ::setExpirationDate
     * @covers ::setIssuingAuthority
     * @covers ::getType
     * @covers ::getIssuingCountry
     * @covers ::getDocumentNumber
     * @covers ::getExpirationDate
     */
    public function testShouldParseOneOptionalAttribute()
    {
        $document = new DocumentDetails('PASSPORT GBR 01234567 2020-01-01');
        $this->assertEquals('PASSPORT', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('01234567', $document->getDocumentNumber());
        $this->assertEquals('2020-01-01', $document->getExpirationDate()->format('Y-m-d'));
    }

    /**
     * @covers ::getType
     * @covers ::getIssuingCountry
     * @covers ::getDocumentNumber
     * @covers ::getExpirationDate
     * @covers ::getIssuingAuthority
     */
    public function testShouldParseTwoOptionalAttributes()
    {
        $document = new DocumentDetails('DRIVING_LICENCE GBR 1234abc 2016-05-01 DVLA');
        $this->assertEquals('DRIVING_LICENCE', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('1234abc', $document->getDocumentNumber());
        $this->assertEquals('2016-05-01', $document->getExpirationDate()->format('Y-m-d'));
        $this->assertEquals('DVLA', $document->getIssuingAuthority());
    }

    /**
     * @covers ::__construct
     * @covers ::validateValue
     */
    public function testShouldThrowExceptionWhenValueIsEmpty()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        $document = new DocumentDetails('');
    }

    /**
     * @covers ::__construct
     * @covers ::validateValue
     */
    public function testShouldThrowExceptionForInvalidCountry()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        $document = new DocumentDetails('PASSPORT 13 1234abc 2016-05-01');
    }

    /**
     * @covers ::__construct
     * @covers ::validateValue
     */
    public function testShouldThrowExceptionForInvalidNumber()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        new DocumentDetails("PASSPORT GBR $%^$%^£ 2016-05-01");
    }

    /**
     * @covers ::getType
     * @covers ::getIssuingCountry
     * @covers ::getDocumentNumber
     * @covers ::getExpirationDate
     * @covers ::getIssuingAuthority
     */
    public function testWhenExpirationDateIsMissing()
    {
        $document = new DocumentDetails('PASS_CARD GBR 22719564893 - CITIZENCARD');
        $this->assertEquals('PASS_CARD', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('22719564893', $document->getDocumentNumber());
        $this->assertNull($document->getExpirationDate());
        $this->assertEquals('CITIZENCARD', $document->getIssuingAuthority());
    }

    /**
     * @covers ::__construct
     */
    public function testWhenTheValueIsLessThanThreeWords()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        new DocumentDetails('PASS_CARD GBR');
    }

    /**
     * @covers ::__construct
     */
    public function testShouldThrowExceptionForInvalidDate()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        new DocumentDetails('PASSPORT GBR 1234abc X016-05-01');
    }

    /**
     * @covers ::getType
     * @covers ::getIssuingCountry
     * @covers ::getDocumentNumber
     * @covers ::getExpirationDate
     * @covers ::getIssuingAuthority
     */
    public function testShouldIgnoreTheSixthOptionalAttribute()
    {
        $value = 'DRIVING_LICENCE GBR 1234abc 2016-05-01 DVLA someThirdAttribute';
        $document = new DocumentDetails($value);

        $this->assertEquals('DRIVING_LICENCE', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('1234abc', $document->getDocumentNumber());
        $this->assertEquals('2016-05-01', $document->getExpirationDate()->format('Y-m-d'));
        $this->assertEquals('DVLA', $document->getIssuingAuthority());
    }
}
