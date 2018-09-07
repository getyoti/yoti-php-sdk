<?php
namespace YotiTest\Entity;

use Yoti\Entity\DocumentDetails;
use YotiTest\TestCase;

class DocumentDetailsTest extends TestCase
{
    /**
     * @var DocumentDetails
     */
    public $dummyDocumentDetails;

    public function setup()
    {
        $dummyValue = 'PASSPORT GBR 01234567 2020-01-01';
        $this->dummyDocumentDetails = new DocumentDetails($dummyValue);
    }

    public function testShouldParseOneOptionalAttribute()
    {
        $document = new DocumentDetails('PASSPORT GBR 01234567 2020-01-01');
        $this->assertEquals('PASSPORT', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('01234567', $document->getDocumentNumber());
        $this->assertEquals('2020-01-01', $document->getExpirationDate()->format('Y-m-d'));
    }

    public function testShouldParseTwoOptionalAttributes()
    {
        $document = new DocumentDetails('DRIVING_LICENCE GBR 1234abc 2016-05-01 DVLA');
        $this->assertEquals('DRIVING_LICENCE', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('1234abc', $document->getDocumentNumber());
        $this->assertEquals('2016-05-01', $document->getExpirationDate()->format('Y-m-d'));
        $this->assertEquals('DVLA', $document->getIssuingAuthority());
    }

    public function testShouldThrowExceptionWhenValueIsEmpty()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        $document = new DocumentDetails('');
    }

    public function testShouldThrowExceptionForInvalidCountry()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        $document = new DocumentDetails('PASSPORT 13 1234abc 2016-05-01');
    }

    public function testShouldThrowExceptionForInvalidNumber()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        $document = new DocumentDetails("PASSPORT GBR $%^$%^£ 2016-05-01");
    }

    public function testWhenExpirationDateIsMissing()
    {
        $document = new DocumentDetails('PASS_CARD GBR 22719564893 - CITIZENCARD');
        $this->assertEquals('PASS_CARD', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('22719564893', $document->getDocumentNumber());
        $this->assertNull($document->getExpirationDate());
        $this->assertEquals('CITIZENCARD', $document->getIssuingAuthority());
    }

    public function testWhenTheValueIsLessThanThreeWords()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        $documentDetails = new DocumentDetails('PASS_CARD GBR');
    }

    public function testShouldThrowExceptionForInvalidDate()
    {
        $this->expectException('Yoti\Exception\AttributeException');
        $document = new DocumentDetails('PASSPORT GBR 1234abc X016-05-01');
    }

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