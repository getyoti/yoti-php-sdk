<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ShareCodeResourceResponse;
use Yoti\DocScan\Session\Retrieve\VerifyShareCodeTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ShareCodeResourceResponse
 */
class ShareCodeResourceResponseTest extends TestCase
{
    private const SOME_ID = 'someId';
    private const SOME_SOURCE = 'someSource';
    private const SOME_CREATED_AT = '2021-01-01T00:00:00.000Z';
    private const SOME_LAST_UPDATED = '2021-01-02T00:00:00.000Z';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCreatedAt
     * @covers ::getLastUpdated
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::getId
     */
    public function shouldParseBasicFields()
    {
        $shareCodeData = [
            'id' => self::SOME_ID,
            'source' => [
                'type' => self::SOME_SOURCE,
            ],
            'created_at' => self::SOME_CREATED_AT,
            'last_updated' => self::SOME_LAST_UPDATED,
        ];

        $shareCodeResource = new ShareCodeResourceResponse($shareCodeData);

        $this->assertEquals(self::SOME_ID, $shareCodeResource->getId());
        $this->assertInstanceOf(\DateTime::class, $shareCodeResource->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $shareCodeResource->getLastUpdated());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLookupProfileMedia
     * @covers ::getReturnedProfileMedia
     * @covers ::getIdPhotoMedia
     * @covers ::getFileMedia
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::__construct
     */
    public function shouldParseMediaFields()
    {
        $shareCodeData = [
            'id' => self::SOME_ID,
            'source' => [
                'type' => self::SOME_SOURCE,
            ],
            'lookup_profile' => [
                'media' => [
                    'id' => 'lookup_profile_media_id',
                    'type' => 'JSON',
                ],
            ],
            'returned_profile' => [
                'media' => [
                    'id' => 'returned_profile_media_id',
                    'type' => 'JSON',
                ],
            ],
            'id_photo' => [
                'media' => [
                    'id' => 'id_photo_media_id',
                    'type' => 'IMAGE',
                ],
            ],
            'file' => [
                'media' => [
                    'id' => 'file_media_id',
                    'type' => 'PDF',
                ],
            ],
        ];

        $shareCodeResource = new ShareCodeResourceResponse($shareCodeData);

        $this->assertNotNull($shareCodeResource->getLookupProfileMedia());
        $this->assertEquals('lookup_profile_media_id', $shareCodeResource->getLookupProfileMedia()->getId());
        
        $this->assertNotNull($shareCodeResource->getReturnedProfileMedia());
        $this->assertEquals('returned_profile_media_id', $shareCodeResource->getReturnedProfileMedia()->getId());
        
        $this->assertNotNull($shareCodeResource->getIdPhotoMedia());
        $this->assertEquals('id_photo_media_id', $shareCodeResource->getIdPhotoMedia()->getId());
        
        $this->assertNotNull($shareCodeResource->getFileMedia());
        $this->assertEquals('file_media_id', $shareCodeResource->getFileMedia()->getId());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLookupProfileMedia
     * @covers ::getReturnedProfileMedia
     * @covers ::getIdPhotoMedia
     * @covers ::getFileMedia
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::__construct
     */
    public function shouldHandleMissingMediaFields()
    {
        $shareCodeData = [
            'id' => self::SOME_ID,
            'source' => [
                'type' => self::SOME_SOURCE,
            ],
        ];

        $shareCodeResource = new ShareCodeResourceResponse($shareCodeData);

        $this->assertNull($shareCodeResource->getLookupProfileMedia());
        $this->assertNull($shareCodeResource->getReturnedProfileMedia());
        $this->assertNull($shareCodeResource->getIdPhotoMedia());
        $this->assertNull($shareCodeResource->getFileMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerifyShareCodeTasks
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::getTasks
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::filterTasksByType
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::createTaskFromArray
     */
    public function shouldParseVerifyShareCodeTasks()
    {
        $shareCodeData = [
            'id' => self::SOME_ID,
            'source' => [
                'type' => self::SOME_SOURCE,
            ],
            'tasks' => [
                [
                    'type' => 'VERIFY_SHARE_CODE_TASK',
                    'id' => 'task_id_1',
                    'state' => 'DONE',
                ],
                [
                    'type' => 'VERIFY_SHARE_CODE_TASK',
                    'id' => 'task_id_2',
                    'state' => 'PENDING',
                ],
            ],
        ];

        $shareCodeResource = new ShareCodeResourceResponse($shareCodeData);

        $this->assertCount(2, $shareCodeResource->getTasks());
        $this->assertCount(2, $shareCodeResource->getVerifyShareCodeTasks());
        $this->assertInstanceOf(VerifyShareCodeTaskResponse::class, $shareCodeResource->getVerifyShareCodeTasks()[0]);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerifyShareCodeTasks
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::filterTasksByType
     */
    public function shouldHandleMissingTasks()
    {
        $shareCodeData = [
            'id' => self::SOME_ID,
            'source' => [
                'type' => self::SOME_SOURCE,
            ],
        ];

        $shareCodeResource = new ShareCodeResourceResponse($shareCodeData);

        $this->assertCount(0, $shareCodeResource->getVerifyShareCodeTasks());
    }
}
