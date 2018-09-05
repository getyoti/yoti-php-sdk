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

    public function testGetType()
    {
        $this->assertEquals('PASSPORT', $this->dummyDocumentDetails->getType());
    }

    public function testGetIssuingCountry()
    {
        $this->assertEquals('GBR', $this->dummyDocumentDetails->getIssuingCountry());
    }

    public function testGetDocumentNumber()
    {
        $this->assertEquals('01234567', $this->dummyDocumentDetails->getDocumentNumber());
    }

    public function testGetExpirationDate()
    {
        $this->assertEquals('2020-01-01', $this->dummyDocumentDetails->getExpirationDate()->format('Y-m-d'));
    }

    public function testGetIssuingAuthority()
    {
        $this->assertEmpty($this->dummyDocumentDetails->getIssuingAuthority());
    }

    public function testExpirationDateAsUnderscore()
    {
        $documentDetails = new DocumentDetails('PASS_CARD GBR 22719564893 - CITIZENCARD');
        $this->assertNull($documentDetails->getExpirationDate());
    }

    public function testWhenTheValueIsLessThanThreeWords()
    {
        $documentDetails = new DocumentDetails('PASS_CARD GBR');
        $this->assertNull($documentDetails->getType());
        $this->assertNull($documentDetails->getIssuingCountry());
        $this->assertNull($documentDetails->getDocumentNumber());
        $this->assertNull($documentDetails->getExpirationDate());
        $this->assertNull($documentDetails->getIssuingAuthority());
    }
}