<?php

declare(strict_types=1);

namespace Yoti\Http\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

class RequestException extends ClientException implements RequestExceptionInterface
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
