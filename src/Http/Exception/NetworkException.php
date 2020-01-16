<?php

declare(strict_types=1);

namespace Yoti\Http\Exception;

use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

class NetworkException extends ClientException implements NetworkExceptionInterface
{
    use RequestAwareTrait;

    /**
     * @param string $message
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Throwable $previous
     */
    public function __construct(
        string $message,
        RequestInterface $request,
        \Throwable $previous = null
    ) {
        $this->setRequest($request);
        parent::__construct($message, 0, $previous);
    }
}
