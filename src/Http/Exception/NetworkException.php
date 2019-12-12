<?php

namespace Yoti\Http\Exception;

use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

class NetworkException extends \Exception implements NetworkExceptionInterface
{
    use RequestAwareTrait;

    /**
     * @param string $message
     * @param \Psr\Http\Client\RequestExceptionInterface $request
     */
    public function __construct($message, RequestInterface $request)
    {
        $this->setRequest($request);
        parent::__construct($message);
    }
}
