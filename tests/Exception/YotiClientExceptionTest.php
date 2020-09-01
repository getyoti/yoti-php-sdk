<?php

declare(strict_types=1);

namespace Yoti\Test\Exception;

use Yoti\Exception\YotiClientException;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Exception\YotiClientException
 */
class YotiClientExceptionTest extends TestCase
{
    public function testInstanceOfException()
    {
        $this->assertInstanceOf(\Exception::class, new YotiClientException());
    }
}
