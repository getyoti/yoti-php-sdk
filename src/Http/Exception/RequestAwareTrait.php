<?php

namespace Yoti\Http\Exception;

use Psr\Http\Message\RequestInterface;

trait RequestAwareTrait
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @inheritDoc
     */
    private function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
