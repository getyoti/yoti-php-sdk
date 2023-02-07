<?php

namespace Yoti\Test\IDV\Exception;

use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use Yoti\IDV\Exception\IDVException;
use Yoti\Test\TestCase;

class IDVExceptionTest extends TestCase
{
    private const SOME_MESSAGE = 'Some message';
    private const SOME_RESPONSE_CODE = 'SOME_ERROR_CODE';
    private const SOME_RESPONSE_MESSAGE = 'Some response message';
    private const SOME_PROPERTY = 'some.property';
    private const SOME_PROPERTY_MESSAGE = 'Some property message';
    private const SOME_OTHER_PROPERTY = 'some.other.property';
    private const SOME_OTHER_PROPERTY_MESSAGE = 'Some other property message';

    /**
     * @test
     */
    public function responseShouldBeOptional()
    {
        $IDVException = new IDVException(self::SOME_MESSAGE);
        $this->assertEquals(self::SOME_MESSAGE, $IDVException->getMessage());
    }

    /**
     * @test
     */
    public function shouldStoreResponse()
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $IDVException = new IDVException(self::SOME_MESSAGE, $responseMock);
        $this->assertEquals(self::SOME_MESSAGE, $IDVException->getMessage());
        $this->assertSame($responseMock, $IDVException->getResponse());
    }

    /**
     * @test
     */
    public function shouldExcludeNonJsonResponsesFromMessage()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('hasHeader')->willReturn(true);
        $responseMock->method('getHeader')->willReturn(['text/html']);

        $IDVException = new IDVException(self::SOME_MESSAGE, $responseMock);

        $this->assertEquals(self::SOME_MESSAGE, $IDVException->getMessage());
        $this->assertEquals($responseMock, $IDVException->getResponse());
    }

    /**
     * @test
     *
     * @dataProvider jsonResponseMessageDataProvider
     */
    public function shouldIncludeFormattedResponseInMessage($message, $jsonData, $expectedMessage)
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('hasHeader')->willReturn(true);
        $responseMock->method('getHeader')->willReturn(['application/json']);
        $responseMock->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode($jsonData)));

        $IDVException = new IDVException($message, $responseMock);

        $this->assertEquals($expectedMessage, $IDVException->getMessage());
        $this->assertEquals($responseMock, $IDVException->getResponse());
    }

    /**
     * Provides JSON response data and their expected exception message.
     *
     * @return array
     */
    public function jsonResponseMessageDataProvider(): array
    {
        return [
            /**
             * Message and code.
             */
            [
                self::SOME_MESSAGE,
                [
                    'message' => self::SOME_RESPONSE_MESSAGE,
                    'code' => self::SOME_RESPONSE_CODE
                ],
                sprintf(
                    '%s - %s - %s',
                    self::SOME_MESSAGE,
                    self::SOME_RESPONSE_CODE,
                    self::SOME_RESPONSE_MESSAGE
                ),
            ],
            /**
             * Message, code and errors with some unknown items
             */
            [
                self::SOME_MESSAGE,
                [
                    'message' => self::SOME_RESPONSE_MESSAGE,
                    'code' => self::SOME_RESPONSE_CODE,
                    'errors' => [
                        [
                            'some_unknown_item' => 'some unknown item',
                            'message' => self::SOME_PROPERTY_MESSAGE,
                        ],
                        [
                            'property' => self::SOME_PROPERTY,
                            'some_unknown_item' => 'some unknown item',
                        ],
                        [
                            'property' => self::SOME_OTHER_PROPERTY,
                            'message' => self::SOME_OTHER_PROPERTY_MESSAGE,
                        ],
                    ],
                ],
                sprintf(
                    '%s - %s - %s: %s "%s"',
                    self::SOME_MESSAGE,
                    self::SOME_RESPONSE_CODE,
                    self::SOME_RESPONSE_MESSAGE,
                    self::SOME_OTHER_PROPERTY,
                    self::SOME_OTHER_PROPERTY_MESSAGE
                ),
            ],
            /**
             * Message, code and errors with unknown items
             */
            [
                self::SOME_MESSAGE,
                [
                    'message' => self::SOME_RESPONSE_MESSAGE,
                    'code' => self::SOME_RESPONSE_CODE,
                    'errors' => [
                        [
                            'some_unknown_item' => 'some unknown item',
                            'message' => self::SOME_PROPERTY_MESSAGE,
                        ],
                        [
                            'property' => self::SOME_PROPERTY,
                            'some_unknown_item' => 'some unknown item',
                        ],
                    ],
                ],
                sprintf(
                    '%s - %s - %s',
                    self::SOME_MESSAGE,
                    self::SOME_RESPONSE_CODE,
                    self::SOME_RESPONSE_MESSAGE
                ),
            ],
            /**
             * Message, code and errors.
             */
            [
                self::SOME_MESSAGE,
                [
                    'message' => self::SOME_RESPONSE_MESSAGE,
                    'code' => self::SOME_RESPONSE_CODE,
                    'errors' => [
                        [
                            'property' => self::SOME_PROPERTY,
                            'message' => self::SOME_PROPERTY_MESSAGE,
                        ],
                        [
                            'property' => self::SOME_OTHER_PROPERTY,
                            'message' => self::SOME_OTHER_PROPERTY_MESSAGE,
                        ],
                    ],
                ],
                sprintf(
                    '%s - %s - %s: %s "%s", %s "%s"',
                    self::SOME_MESSAGE,
                    self::SOME_RESPONSE_CODE,
                    self::SOME_RESPONSE_MESSAGE,
                    self::SOME_PROPERTY,
                    self::SOME_PROPERTY_MESSAGE,
                    self::SOME_OTHER_PROPERTY,
                    self::SOME_OTHER_PROPERTY_MESSAGE
                ),
            ],
            /**
             * Message without code.
             */
            [
                self::SOME_MESSAGE,
                [
                    'message' => self::SOME_RESPONSE_MESSAGE,
                ],
                self::SOME_MESSAGE,
            ],
            /**
             * Code without message.
             */
            [
                self::SOME_MESSAGE,
                [
                    'code' => self::SOME_RESPONSE_CODE,
                ],
                self::SOME_MESSAGE,
            ],
        ];
    }
}
