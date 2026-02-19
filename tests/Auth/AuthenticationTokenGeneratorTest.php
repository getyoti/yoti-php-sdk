<?php

declare(strict_types=1);

namespace Yoti\Test\Auth;

use Yoti\Auth\AuthenticationTokenGenerator;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Auth\AuthenticationTokenGenerator
 */
class AuthenticationTokenGeneratorTest extends TestCase
{
    private const SOME_SDK_ID = 'some-sdk-id';
    private const SOME_JWT_ID = 'some-jwt-id';
    private const SOME_AUTH_URL = 'https://auth.api.yoti.com/v1/oauth/token';

    /**
     * @test
     * @covers ::__construct
     * @covers ::builder
     */
    public function shouldCreateViaBuilderStaticMethod()
    {
        $builder = AuthenticationTokenGenerator::builder();
        $this->assertInstanceOf(\Yoti\Auth\Builder::class, $builder);
    }

    /**
     * @test
     * @covers ::generate
     */
    public function shouldThrowOnEmptyScopes()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);

        $generator = new AuthenticationTokenGenerator(
            self::SOME_SDK_ID,
            $pemFile,
            function () {
                return self::SOME_JWT_ID;
            },
            self::SOME_AUTH_URL
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('scopes must not be empty');

        $generator->generate([]);
    }
}
