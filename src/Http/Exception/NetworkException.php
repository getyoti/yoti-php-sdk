<?php

namespace Yoti\Http\Exception;

use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

class NetworkException extends ClientException implements NetworkExceptionInterface
{
    use RequestAwareTrait;

    /**
     * @param string $message
     * @param \Psr\Http\Client\RequestExceptionInterface $request
     * @param integer $code
     * @param \Throwable $previous
     */
    public function __construct($message, RequestInterface $request, $code = null, \Throwable $previous = null)
    {
        $this->setRequest($request);
        parent::__construct($message, $code, $previous);
    }
}
