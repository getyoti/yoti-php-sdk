<?php

namespace Yoti\Test\Identity\Content;

use Yoti\Identity\Content\ApplicationContent;
use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\ExtraData;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Content\ApplicationContent
 */
class ApplicationContentTest extends TestCase
{
    /**
     * @covers ::getExtraData
     * @covers ::getProfile
     * @covers ::__construct
     */
    public function testBuildCorrectly()
    {
        $applicationProfile = $this->createMock(ApplicationProfile::class);
        $extraData = $this->createMock(ExtraData::class);

        $applicationContent = new ApplicationContent($applicationProfile, $extraData);

        $this->assertInstanceOf(ApplicationProfile::class, $applicationContent->getProfile());
        $this->assertInstanceOf(ExtraData::class, $applicationContent->getExtraData());

        $applicationContent2 = new ApplicationContent();

        $this->assertNull($applicationContent2->getProfile());
        $this->assertNull($applicationContent2->getExtraData());
    }
}
