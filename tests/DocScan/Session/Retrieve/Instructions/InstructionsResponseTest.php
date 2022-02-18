<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Instructions;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Instructions\Branch\BranchResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Branch\UkPostOfficeBranchResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Branch\UnknownBranchResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\InstructionsResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Instructions\InstructionsResponse
 */
class InstructionsResponseTest extends TestCase
{
    private const SOME_REQUIREMENT_ID = "SOME_ID";
    private const SOME_REQUIREMENT_ID_2 = "SOME_ID_2";

    private const SOME_COUNTRY_CODE = "SOME_COUNTRY_CODE";
    private const SOME_DOCUMENT_TYPE = "SOME_DOCUMENT_TYPE";

    private const SOME_COUNTRY_CODE_2 = "SOME_COUNTRY_CODE_2";
    private const SOME_DOCUMENT_TYPE_2 = "SOME_DOCUMENT_TYPE_2";

    private const SOME_NAME = 'SOME_NAME';
    private const SOME_ADDRESS = 'SOME_ADDRESS';
    private const SOME_POST_CODE = 'SOME_POST_CODE';
    private const SOME_FAD_CODE = 'SOME_FAD_CODE';
    private const SOME_LATITUDE = 0.0873;
    private const SOME_LONGITUDE = 0.836793;

    /**
     * @test
     * @covers ::isContactProfileExists
     * @covers ::getBranch
     * @covers ::getDocuments
     * @covers ::__construct
     */
    public function shouldBuildCorrectly()
    {
        $data = [
            'contact_profile_exists' => true,
            'documents' =>
                [
                    [
                        'requirement_id' => self::SOME_REQUIREMENT_ID,
                        'document' => [
                            'type' => Constants::ID_DOCUMENT,
                            'country_code' => self::SOME_COUNTRY_CODE,
                            'document_type' => self::SOME_DOCUMENT_TYPE
                        ],
                    ],

                    [
                        'requirement_id' => self::SOME_REQUIREMENT_ID_2,
                        'document' => [
                            'type' => Constants::SUPPLEMENTARY_DOCUMENT,
                            'country_code' => self::SOME_COUNTRY_CODE_2,
                            'document_type' => self::SOME_DOCUMENT_TYPE_2
                        ],
                    ]
                ],
            'branch' => [
                'type' => Constants::UK_POST_OFFICE,
                'fad_code' => self::SOME_FAD_CODE,
                'name' => self::SOME_NAME,
                'address' => self::SOME_ADDRESS,
                'post_code' => self::SOME_POST_CODE,
                'location' => [
                    'latitude' => self::SOME_LATITUDE,
                    'longitude' => self::SOME_LONGITUDE,
                ]
            ]
        ];

        $data2 = [
            'contact_profile_exists' => true,
            'documents' =>
                [
                    [
                        'requirement_id' => self::SOME_REQUIREMENT_ID,
                        'document' => [
                            'type' => Constants::ID_DOCUMENT,
                            'country_code' => self::SOME_COUNTRY_CODE,
                            'document_type' => self::SOME_DOCUMENT_TYPE
                        ],
                    ],

                    [
                        'requirement_id' => self::SOME_REQUIREMENT_ID_2,
                        'document' => [
                            'type' => Constants::SUPPLEMENTARY_DOCUMENT,
                            'country_code' => self::SOME_COUNTRY_CODE_2,
                            'document_type' => self::SOME_DOCUMENT_TYPE_2
                        ],
                    ]
                ],
            'branch' => [
                'type' => 'SOME_TYPE',
                'fad_code' => self::SOME_FAD_CODE,
                'name' => self::SOME_NAME,
                'address' => self::SOME_ADDRESS,
                'post_code' => self::SOME_POST_CODE,
                'location' => [
                    'latitude' => self::SOME_LATITUDE,
                    'longitude' => self::SOME_LONGITUDE,
                ]
            ]
        ];


        $result = new InstructionsResponse($data);

        $this->assertTrue($result->isContactProfileExists());
        $this->assertInstanceOf(UkPostOfficeBranchResponse::class, $result->getBranch());
        $this->assertCount(2, $result->getDocuments());

        $result2 = new InstructionsResponse($data2);
        $this->assertInstanceOf(UnknownBranchResponse::class, $result2->getBranch());
        $result = new InstructionsResponse($data);

        $this->assertTrue($result->isContactProfileExists());
        $this->assertInstanceOf(BranchResponse::class, $result->getBranch());
        $this->assertCount(2, $result->getDocuments());
    }
}
