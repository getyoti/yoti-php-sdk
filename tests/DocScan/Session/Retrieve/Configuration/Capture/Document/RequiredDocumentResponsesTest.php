<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration\Capture\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\ObjectiveResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\RequiredIdDocumentResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\RequiredSupplementaryDocumentResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedIdDocTaskResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedSupplementaryDocTaskResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\UnknownRequestedTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\RequiredDocumentResourceResponse
 */
class RequiredDocumentResponsesTest extends TestCase
{
    private const SOME_TYPE = 'SOME_TYPE';
    private const SOME_ID = 'SOME_ID';
    private const SOME_STATE = 'SOME_STATE';
    private const SOME_CODE = 'SOME_CODE';
    private const SOME_METHODS = 'SOME_METHODS';
    private const SOME_ATTEMPTS = [
        'some' => 5
    ];

    private const SOME_ALLOWED_SOURCES = [
        [
            'type' => Constants::END_USER,
        ],
        [
            'type' => Constants::RELYING_BUSINESS,
        ],
        [
            'type' => Constants::IBV,
        ],
    ];

    private const SOME_DOCUMENT_TYPES = ['one', 'two', 'three'];
    private const SOME_COUNTRY_CODES = ['one', 'two', 'three'];
    private const SOME_OBJECTIVE = ['type' => 'SOME_TYPE'];

    /**
     * @test
     * @covers ::createTaskFromArray
     * @covers ::getRequestedTasks
     * @covers \RequiredIdDocumentResourceResponse::__construct
     * @covers \RequiredIdDocumentResourceResponse::getSupportedCountries
     * @covers \RequiredIdDocumentResourceResponse::getAllowedCaptureMethods
     * @covers \RequiredIdDocumentResourceResponse::getAttemptsRemaining
     * @covers \RequiredSupplementaryDocumentResourceResponse::__construct
     * @covers \RequiredSupplementaryDocumentResourceResponse::getDocumentTypes
     * @covers \RequiredSupplementaryDocumentResourceResponse::getCountryCodes
     * @covers \RequiredSupplementaryDocumentResourceResponse::getObjective
     */
    public function shouldBuildCorrectly()
    {
        $inputForId = [
            'type' => self::SOME_TYPE,
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'allowed_sources' => self::SOME_ALLOWED_SOURCES,
            'requested_tasks' => [
                ['type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION'],
                ['type' => 'SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION'],
                ['type' => 'UNKNOWN'],
            ],
            'supported_countries' => [
                ['code' => self::SOME_CODE,
                    'supported_documents' => [
                        ['type' => 'ONE_TYPE'],
                        ['type' => 'SECOND_TYPE'],
                    ]
                ]
            ],
            'allowed_capture_methods' => self::SOME_METHODS,
            'attempts_remaining' => self::SOME_ATTEMPTS
        ];

        $requiredIdDocumentResourceResponse = new RequiredIdDocumentResourceResponse($inputForId);

        $this->assertEquals(self::SOME_METHODS, $requiredIdDocumentResourceResponse->getAllowedCaptureMethods());
        $this->assertEquals(self::SOME_ATTEMPTS, $requiredIdDocumentResourceResponse->getAttemptsRemaining());
        $this->assertCount(1, $requiredIdDocumentResourceResponse->getSupportedCountries());

        $expectedRequestedTasks = [
            new RequestedIdDocTaskResponse(['type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION']),
            new RequestedSupplementaryDocTaskResponse(['type' => 'SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION']),
            new UnknownRequestedTaskResponse(),
        ];

        $inputForSupplementary = [
            'type' => self::SOME_TYPE,
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'allowed_sources' => self::SOME_ALLOWED_SOURCES,
            'requested_tasks' => [
                ['type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION'],
                ['type' => 'SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION'],
                ['type' => 'UNKNOWN'],
            ],
            'document_types' => self::SOME_DOCUMENT_TYPES,
            'country_codes' => self::SOME_COUNTRY_CODES,
            'objective' => self::SOME_OBJECTIVE
        ];


        $requiredSupplementaryDocumentResourceResponse = new RequiredSupplementaryDocumentResourceResponse(
            $inputForSupplementary
        );

        $this->assertEquals(
            self::SOME_DOCUMENT_TYPES,
            $requiredSupplementaryDocumentResourceResponse->getDocumentTypes()
        );
        $this->assertEquals(
            self::SOME_COUNTRY_CODES,
            $requiredSupplementaryDocumentResourceResponse->getCountryCodes()
        );
        $this->assertInstanceOf(
            ObjectiveResponse::class,
            $requiredSupplementaryDocumentResourceResponse->getObjective()
        );
        $this->assertEquals(
            $expectedRequestedTasks,
            $requiredSupplementaryDocumentResourceResponse->getRequestedTasks()
        );
    }
}
