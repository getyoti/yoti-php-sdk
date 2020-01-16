<?php

declare(strict_types=1);

namespace Yoti\Http\Exception;

use Psr\Http\Client\ClientExceptionInterface;

class ClientException extends \RuntimeException implements ClientExceptionInterface
{
}
