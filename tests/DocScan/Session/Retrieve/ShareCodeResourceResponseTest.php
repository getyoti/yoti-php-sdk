<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\DocScan\Session\Retrieve\ShareCodeMediaResponse;
use Yoti\DocScan\Session\Retrieve\ShareCodeResourceResponse;
use Yoti\DocScan\Session\Retrieve\VerifyShareCodeTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ShareCodeResourceResponse
 */
class ShareCodeResourceResponseTest extends TestCase
{
    private const VERIFY_SHARE_CODE_TASK = 'VERIFY_SHARE_CODE_TASK';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCreatedAt
     * @covers ::getLastUpdated
     * @covers ::getLookupProfile
     * @covers ::getReturnedProfile
     * @covers ::getIdPhoto
     * @covers ::getFile
     * @covers ::getVerifyShareCodeTasks
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id' => 'share-code-123',
            'source' => ['type' => 'END_USER'],
            'created_at' => '2026-01-14T10:00:00Z',
            'last_updated' => '2026-01-14T11:00:00Z',
            'lookup_profile' => [
                'media' => ['id' => 'media-1', 'type' => 'JSON'],
            ],
            'returned_profile' => [
                'media' => ['id' => 'media-2', 'type' => 'JSON'],
            ],
            'id_photo' => [
                'media' => ['id' => 'media-3', 'type' => 'IMAGE'],
            ],
            'file' => [
                'media' => ['id' => 'media-4', 'type' => 'PDF'],
            ],
            'tasks' => [
                [
                    'type' => self::VERIFY_SHARE_CODE_TASK,
                    'id' => 'task-123',
                    'state' => 'DONE',
                    'created' => '2026-01-14T10:00:00Z',
                    'last_updated' => '2026-01-14T11:00:00Z',
                    'generated_media' => [
                        ['id' => 'gm-1', 'type' => 'PDF'],
                        ['id' => 'gm-2', 'type' => 'IMAGE'],
                    ],
                ],
            ],
        ];

        $result = new ShareCodeResourceResponse($input);

        $this->assertEquals('share-code-123', $result->getId());
        $this->assertEquals('2026-01-14T10:00:00Z', $result->getCreatedAt());
        $this->assertEquals('2026-01-14T11:00:00Z', $result->getLastUpdated());

        $this->assertInstanceOf(ShareCodeMediaResponse::class, $result->getLookupProfile());
        $this->assertInstanceOf(MediaResponse::class, $result->getLookupProfile()->getMedia());
        $this->assertEquals('media-1', $result->getLookupProfile()->getMedia()->getId());
        $this->assertEquals('JSON', $result->getLookupProfile()->getMedia()->getType());

        $this->assertInstanceOf(ShareCodeMediaResponse::class, $result->getReturnedProfile());
        $this->assertEquals('media-2', $result->getReturnedProfile()->getMedia()->getId());

        $this->assertInstanceOf(ShareCodeMediaResponse::class, $result->getIdPhoto());
        $this->assertEquals('media-3', $result->getIdPhoto()->getMedia()->getId());
        $this->assertEquals('IMAGE', $result->getIdPhoto()->getMedia()->getType());

        $this->assertInstanceOf(ShareCodeMediaResponse::class, $result->getFile());
        $this->assertEquals('media-4', $result->getFile()->getMedia()->getId());
        $this->assertEquals('PDF', $result->getFile()->getMedia()->getType());

        $this->assertCount(1, $result->getVerifyShareCodeTasks());
        $this->assertContainsOnlyInstancesOf(
            VerifyShareCodeTaskResponse::class,
            $result->getVerifyShareCodeTasks()
        );
        $this->assertEquals('task-123', $result->getVerifyShareCodeTasks()[0]->getId());
        $this->assertEquals('DONE', $result->getVerifyShareCodeTasks()[0]->getState());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new ShareCodeResourceResponse([]);

        $this->assertNull($result->getId());
        $this->assertNull($result->getCreatedAt());
        $this->assertNull($result->getLastUpdated());
        $this->assertNull($result->getLookupProfile());
        $this->assertNull($result->getReturnedProfile());
        $this->assertNull($result->getIdPhoto());
        $this->assertNull($result->getFile());
        $this->assertCount(0, $result->getVerifyShareCodeTasks());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerifyShareCodeTasks
     */
    public function shouldFilterVerifyShareCodeTasks()
    {
        $input = [
            'id' => 'share-code-mixed',
            'tasks' => [
                [
                    'type' => self::VERIFY_SHARE_CODE_TASK,
                    'id' => 'task-verify',
                    'state' => 'DONE',
                ],
                [
                    'type' => 'OTHER_TASK_TYPE',
                    'id' => 'task-other',
                    'state' => 'PENDING',
                ],
            ],
        ];

        $result = new ShareCodeResourceResponse($input);

        $this->assertCount(2, $result->getTasks());
        $this->assertCount(1, $result->getVerifyShareCodeTasks());
        $this->assertEquals('task-verify', $result->getVerifyShareCodeTasks()[0]->getId());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerifyShareCodeTasks
     */
    public function shouldHandleMultipleVerifyShareCodeTasks()
    {
        $input = [
            'id' => 'share-code-multi',
            'tasks' => [
                [
                    'type' => self::VERIFY_SHARE_CODE_TASK,
                    'id' => 'task-1',
                    'state' => 'PENDING',
                ],
                [
                    'type' => self::VERIFY_SHARE_CODE_TASK,
                    'id' => 'task-2',
                    'state' => 'DONE',
                ],
            ],
        ];

        $result = new ShareCodeResourceResponse($input);

        $this->assertCount(2, $result->getVerifyShareCodeTasks());
        $this->assertEquals('task-1', $result->getVerifyShareCodeTasks()[0]->getId());
        $this->assertEquals('task-2', $result->getVerifyShareCodeTasks()[1]->getId());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getVerifyShareCodeTasks
     */
    public function shouldHandleNoTasks()
    {
        $input = [
            'id' => 'share-code-no-tasks',
            'tasks' => [],
        ];

        $result = new ShareCodeResourceResponse($input);

        $this->assertCount(0, $result->getVerifyShareCodeTasks());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLookupProfile
     * @covers ::getReturnedProfile
     * @covers ::getIdPhoto
     * @covers ::getFile
     */
    public function shouldHandlePartialMediaFields()
    {
        $input = [
            'id' => 'share-code-partial',
            'lookup_profile' => [
                'media' => ['id' => 'media-1', 'type' => 'JSON'],
            ],
        ];

        $result = new ShareCodeResourceResponse($input);

        $this->assertInstanceOf(ShareCodeMediaResponse::class, $result->getLookupProfile());
        $this->assertEquals('media-1', $result->getLookupProfile()->getMedia()->getId());
        $this->assertNull($result->getReturnedProfile());
        $this->assertNull($result->getIdPhoto());
        $this->assertNull($result->getFile());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldHandleEmptyMediaObjects()
    {
        $input = [
            'id' => 'share-code-empty-media',
            'id_photo' => [],
        ];

        $result = new ShareCodeResourceResponse($input);

        $this->assertInstanceOf(ShareCodeMediaResponse::class, $result->getIdPhoto());
        $this->assertNull($result->getIdPhoto()->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCreatedAt
     * @covers ::getLastUpdated
     * @covers ::getLookupProfile
     * @covers ::getReturnedProfile
     * @covers ::getIdPhoto
     * @covers ::getFile
     * @covers ::getVerifyShareCodeTasks
     */
    public function shouldHandleFullRealisticPayload()
    {
        $input = [
            'id' => 'abc12345-6789-abcd-ef01-234567890abc',
            'source' => ['type' => 'END_USER'],
            'created_at' => '2026-02-05T11:33:46Z',
            'last_updated' => '2026-02-05T11:33:50Z',
            'lookup_profile' => [
                'media' => [
                    'id' => 'df419a66-0449-41cf-a795-6dfaa993d1f6',
                    'type' => 'JSON',
                    'created' => '2026-02-05T11:33:46Z',
                    'last_updated' => '2026-02-05T11:33:50Z',
                ],
            ],
            'returned_profile' => [
                'media' => [
                    'id' => 'f2152059-2868-47c9-8f5f-64966c1b66b0',
                    'type' => 'JSON',
                    'created' => '2026-02-05T11:33:46Z',
                    'last_updated' => '2026-02-05T11:33:50Z',
                ],
            ],
            'id_photo' => [
                'media' => [
                    'id' => '45e4ee9d-a77b-4007-afe9-ab7067687aff',
                    'type' => 'IMAGE',
                    'created' => '2026-02-05T11:33:46Z',
                    'last_updated' => '2026-02-05T11:33:50Z',
                ],
            ],
            'file' => [
                'media' => [
                    'id' => 'c83a9f12-1234-5678-9abc-def012345678',
                    'type' => 'PDF',
                    'created' => '2026-02-05T11:33:46Z',
                    'last_updated' => '2026-02-05T11:33:50Z',
                ],
            ],
            'tasks' => [
                [
                    'type' => self::VERIFY_SHARE_CODE_TASK,
                    'id' => '73141aa9-a01f-4de9-9281-1b11cda7ab75',
                    'state' => 'DONE',
                    'created' => '2026-02-05T11:33:46Z',
                    'last_updated' => '2026-02-05T11:33:50Z',
                    'generated_media' => [
                        ['id' => 'df419a66-0449-41cf-a795-6dfaa993d1f6', 'type' => 'PDF'],
                        ['id' => '45e4ee9d-a77b-4007-afe9-ab7067687aff', 'type' => 'IMAGE'],
                        ['id' => 'f2152059-2868-47c9-8f5f-64966c1b66b0', 'type' => 'JSON'],
                    ],
                ],
            ],
        ];

        $result = new ShareCodeResourceResponse($input);

        $this->assertEquals('abc12345-6789-abcd-ef01-234567890abc', $result->getId());
        $this->assertEquals('2026-02-05T11:33:46Z', $result->getCreatedAt());
        $this->assertEquals('2026-02-05T11:33:50Z', $result->getLastUpdated());

        $this->assertNotNull($result->getLookupProfile());
        $this->assertEquals('df419a66-0449-41cf-a795-6dfaa993d1f6', $result->getLookupProfile()->getMedia()->getId());
        $this->assertEquals('JSON', $result->getLookupProfile()->getMedia()->getType());
        $this->assertNotNull($result->getLookupProfile()->getMedia()->getCreated());
        $this->assertNotNull($result->getLookupProfile()->getMedia()->getLastUpdated());

        $this->assertNotNull($result->getReturnedProfile());
        $this->assertEquals('f2152059-2868-47c9-8f5f-64966c1b66b0', $result->getReturnedProfile()->getMedia()->getId());

        $this->assertNotNull($result->getIdPhoto());
        $this->assertEquals('45e4ee9d-a77b-4007-afe9-ab7067687aff', $result->getIdPhoto()->getMedia()->getId());
        $this->assertEquals('IMAGE', $result->getIdPhoto()->getMedia()->getType());

        $this->assertNotNull($result->getFile());
        $this->assertEquals('c83a9f12-1234-5678-9abc-def012345678', $result->getFile()->getMedia()->getId());
        $this->assertEquals('PDF', $result->getFile()->getMedia()->getType());

        $this->assertCount(1, $result->getVerifyShareCodeTasks());
        $this->assertEquals('DONE', $result->getVerifyShareCodeTasks()[0]->getState());
        $this->assertCount(3, $result->getVerifyShareCodeTasks()[0]->getGeneratedMedia());
    }
}
