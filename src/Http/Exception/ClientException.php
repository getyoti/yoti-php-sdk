<?php

namespace Yoti\Http\Exception;

use Psr\Http\Client\ClientExceptionInterface;

class ClientException extends \RuntimeException implements ClientExceptionInterface
{
}
