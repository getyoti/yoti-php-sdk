<?php

namespace Yoti\Test\DocScan\Exception;

use Psr\Http\Message\ResponseInterface;
use Yoti\DocScan\Exception\DocScanException;
use Yoti\Test\TestCase;

class DocScanExceptionTest extends TestCase
{

    private const SOME_ERROR_MESSAGE = 'Some Error Message';

    /**
     * @test
     */
    public function shouldStoreResponse()
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $docScanException = new DocScanException(self::SOME_ERROR_MESSAGE, $responseMock);
        $this->assertEquals(self::SOME_ERROR_MESSAGE, $docScanException->getMessage());
        $this->assertEquals($responseMock, $docScanException->getResponse());
    }
}
