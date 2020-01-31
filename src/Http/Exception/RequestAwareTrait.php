<?php

declare(strict_types=1);

namespace Yoti\Http\Exception;

use Psr\Http\Message\RequestInterface;

trait RequestAwareTrait
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     */
    private function setRequest(RequestInterface $request): void
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
