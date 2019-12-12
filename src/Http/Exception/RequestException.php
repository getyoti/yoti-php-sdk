<?php

namespace Yoti\Http\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

class RequestException extends \Exception implements RequestExceptionInterface
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
