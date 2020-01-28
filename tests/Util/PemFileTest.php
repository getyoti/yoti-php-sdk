<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Util\PemFile
 */
class PemFileTest extends TestCase
{
    /**
     * @var string
     */
    private $pemContent;

    /**
     * Setup tests.
     */
    public function setup(): void
    {
        parent::setup();
        $this->pemContent = file_get_contents(TestData::PEM_FILE);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testConstructFromString()
    {
        $pemFile = new PemFile($this->pemContent);
        $this->assertEquals($pemFile, $this->pemContent);
    }

    /**
     * @covers ::fromFilePath
     * @covers ::__toString
     */
    public function testFromFilePath()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $this->assertInstanceOf(PemFile::class, $pemFile);
        $this->assertEquals($pemFile, $this->pemContent);
    }

    /**
     * @covers ::fromString
     * @covers ::__toString
     */
    public function testFromString()
    {
        $pemFile = PemFile::fromString($this->pemContent);
        $this->assertInstanceOf(PemFile::class, $pemFile);
        $this->assertEquals($pemFile, $this->pemContent);
    }

    /**
     * @covers ::resolveFromString
     * @covers ::isPemString
     * @covers ::__toString
     */
    public function testResolveFromStringWithPemStringContent()
    {
        $pemFile = PemFile::resolveFromString($this->pemContent);
        $this->assertInstanceOf(PemFile::class, $pemFile);
        $this->assertEquals($pemFile, $this->pemContent);
    }

    /**
     * @covers ::resolveFromString
     * @covers ::isPemString
     * @covers ::__toString
     */
    public function testResolveFromStringWithFilePath()
    {
        $pemFile = PemFile::resolveFromString(TestData::PEM_FILE);
        $this->assertInstanceOf(PemFile::class, $pemFile);
        $this->assertEquals($pemFile, $this->pemContent);
    }

    /**
     * @covers ::resolveFromString
     * @covers ::isPemString
     * @covers ::__toString
     */
    public function testResolveFromStringWithInvalidFilePath()
    {
        $this->expectException(\Yoti\Exception\PemFileException::class);
        $this->expectExceptionMessage('PEM file was not found');

        PemFile::resolveFromString('file://invalid_file_path.pem');
    }

    /**
     * @covers ::resolveFromString
     * @covers ::isPemString
     * @covers ::__toString
     */
    public function testResolveFromStringWithInvalidStringContent()
    {
        $this->expectException(\Yoti\Exception\PemFileException::class);
        $this->expectExceptionMessage('PEM content is invalid');

        PemFile::resolveFromString(file_get_contents(TestData::INVALID_PEM_FILE));
    }

    /**
     * Test passing invalid pem file path with file:// stream wrapper
     *
     * @covers ::__construct
     * @covers ::fromFilePath
     */
    public function testInvalidPemFileStreamWrapperPath()
    {
        $this->expectException(\Yoti\Exception\PemFileException::class);
        $this->expectExceptionMessage('PEM file was not found');

        PemFile::fromFilePath('file://invalid_file_path.pem');
    }

    /**
     * Test passing pem file with invalid contents
     *
     * @covers ::__construct
     * @covers ::fromFilePath
     */
    public function testInvalidPemFileContents()
    {
        $this->expectException(\Yoti\Exception\PemFileException::class);
        $this->expectExceptionMessage('PEM content is invalid');

        PemFile::fromFilePath(TestData::INVALID_PEM_FILE);
    }

    /**
     * Test passing invalid pem string
     *
     * @covers ::__construct
     * @covers ::fromString
     */
    public function testInvalidPemString()
    {
        $this->expectException(\Yoti\Exception\PemFileException::class);
        $this->expectExceptionMessage('PEM content is invalid');

        PemFile::fromString('invalid_pem_string');
    }

    /**
     * Test pem auth key
     *
     * @covers ::getAuthKey
     */
    public function testPemFileAuthKey()
    {
        $pemFile = new PemFile($this->pemContent);
        $this->assertEquals($pemFile->getAuthKey(), file_get_contents(TestData::PEM_AUTH_KEY));
    }

    /**
     * @covers ::getAuthKey
     */
    public function testPublicKeyMissing()
    {
        $this->expectException(\Yoti\Exception\PemFileException::class);
        $this->expectExceptionMessage('Could not extract public key');

        self::mockFunction('openssl_pkey_get_details', function () {
            return [];
        });

        PemFile::fromFilePath(TestData::PEM_FILE)->getAuthKey();
    }

    /**
     * @covers ::getAuthKey
     */
    public function testPublicKeyInvalid()
    {
        $this->expectException(\Yoti\Exception\PemFileException::class);
        $this->expectExceptionMessage('Could not retrieve Auth key from PEM content.');

        self::mockFunction('openssl_pkey_get_details', function () {
            return [
                'key' => 'some-invalid-key',
            ];
        });

        PemFile::fromFilePath(TestData::PEM_FILE)->getAuthKey();
    }
}

/**
 * Mock functions in the Util namespace.
 */
namespace Yoti\Util;

/**
 * Mocks \openssl_pkey_get_details()
 */
function openssl_pkey_get_details()
{
    return \Yoti\Test\TestCase::callMockFunction(__FUNCTION__, func_get_args());
}
