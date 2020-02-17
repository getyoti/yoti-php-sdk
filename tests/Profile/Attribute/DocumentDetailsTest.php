<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Attribute;

use Yoti\Profile\Attribute\DocumentDetails;
use Yoti\Test\TestCase;

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
     * @covers ::parseFromValue
     */
    public function testShouldThrowExceptionWhenValueIsEmpty()
    {
        $this->expectException(\Yoti\Exception\AttributeException::class);
        $this->expectExceptionMessage('Invalid value for DocumentDetails');

        new DocumentDetails('');
    }

    /**
     * @covers ::__construct
     * @covers ::parseFromValue
     *
     * @dataProvider valueWithExtraSpacesDataProvider
     */
    public function testShouldNotAllowExtraSpaces($value)
    {
        $this->expectException(\Yoti\Exception\AttributeException::class);
        $this->expectExceptionMessage('Invalid value for DocumentDetails');

        new DocumentDetails($value);
    }

    /**
     * Value with extra spaces data provider.
     */
    public function valueWithExtraSpacesDataProvider()
    {
        return [
            [ 'some-type   some-country some-doc-number - some-authority' ],
            [ 'some-type some-country  some-doc-number - some-authority' ],
            [ 'some-type some-country some-doc-number  - some-authority' ],
            [ 'some-type some-country some-doc-number -  some-authority' ],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getDocumentNumber
     *
     * @dataProvider validValueDataProvider
     */
    public function testShouldAllowValidDocumentNumbers($validDocumentNumber)
    {
        $document = new DocumentDetails("some-type some-country $validDocumentNumber - some-authority");
        $this->assertEquals($validDocumentNumber, $document->getDocumentNumber());
    }

    /**
     * Valid value data provider.
     */
    public function validValueDataProvider()
    {
        return [
            [ '****' ],
            [ '~!@#$%^&*()-_=+[]{}|;\':,./<>?' ],
            [ '""' ],
            [ '\\' ],
            [ '"' ],
            [ '\'\'' ],
            [ '\'' ],
        ];
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
        $this->expectException(\Yoti\Exception\AttributeException::class);
        new DocumentDetails('PASS_CARD GBR');
    }

    /**
     * @covers ::__construct
     * @covers ::setExpirationDate
     */
    public function testShouldThrowExceptionForInvalidDate()
    {
        $this->expectException(\Yoti\Exception\AttributeException::class);
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
