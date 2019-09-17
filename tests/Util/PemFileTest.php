<?php

namespace YotiTest\Util;

use YotiTest\TestCase;
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
    public function setup()
    {
        $this->pemContent = file_get_contents(PEM_FILE);
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
        $pemFile = PemFile::fromFilePath(PEM_FILE);
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
     * Test passing invalid pem file path with file:// stream wrapper
     *
     * @covers ::__construct
     * @covers ::fromFilePath
     *
     * @expectedException \Yoti\Exception\PemFileException
     * @expectedExceptionMessage PEM file was not found
     */
    public function testInvalidPemFileStreamWrapperPath()
    {
        PemFile::fromFilePath('file://invalid_file_path.pem');
    }

    /**
     * Test passing pem file with invalid contents
     *
     * @covers ::__construct
     * @covers ::fromFilePath
     *
     * @expectedException \Yoti\Exception\PemFileException
     * @expectedExceptionMessage PEM content is invalid
     */
    public function testInvalidPemFileContents()
    {
        PemFile::fromFilePath(INVALID_PEM_FILE);
    }

    /**
     * Test passing invalid pem string
     *
     * @covers ::__construct
     * @covers ::fromString
     *
     * @expectedException \Yoti\Exception\PemFileException
     * @expectedExceptionMessage PEM content is invalid
     */
    public function testInvalidPemString()
    {
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
        $this->assertEquals($pemFile->getAuthKey(), PEM_AUTH_KEY);
    }
}
