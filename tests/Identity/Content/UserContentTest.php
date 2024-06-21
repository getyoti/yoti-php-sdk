<?php

namespace Yoti\Test\Identity\Content;

use Yoti\Identity\Content\UserContent;
use Yoti\Profile\ExtraData;
use Yoti\Profile\UserProfile;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Content\UserContent
 */
class UserContentTest extends TestCase
{
    /**
     * @covers ::getExtraData
     * @covers ::getProfile
     * @covers ::__construct
     */
    public function testBuildCorrectly()
    {
        $userProfile = $this->createMock(UserProfile::class);
        $extraData = $this->createMock(ExtraData::class);

        $userContent = new UserContent($userProfile, $extraData);

        $this->assertInstanceOf(UserProfile::class, $userContent->getProfile());
        $this->assertInstanceOf(ExtraData::class, $userContent->getExtraData());

        $userContent2 = new UserContent();

        $this->assertNull($userContent2->getProfile());
        $this->assertNull($userContent2->getExtraData());
    }
}
