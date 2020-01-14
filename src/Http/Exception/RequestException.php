<?php

namespace Yoti\Http\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

class RequestException extends ClientException implements RequestExceptionInterface
{
    use RequestAwareTrait;

    /**
     * @param string $message
     * @param \Psr\Http\Client\RequestExceptionInterface $request
     * @param integer $code
     * @param \Throwable $previous
     */
    public function __construct($message, RequestInterface $request, $code = 0, \Throwable $previous = null)
    {
        $this->setRequest($request);
        parent::__construct($message, $code, $previous);
    }
}
