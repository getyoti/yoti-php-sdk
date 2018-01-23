<?php
namespace Yoti\Http;

abstract class AbstractRequest
{
    private $headers;
    private $url;

    public function __construct(array $headers, $url)
    {
        $this->setHeaders($headers);
        $this->setUrl($url);
    }

    public function setHeaders(array $headers)
    {
        if(empty($headers)) {
            throw new \Exception('Request headers cannot be empty');
        }

        $this->headers = $headers;
    }

    public function setUrl($url)
    {
        if(empty($url)) {
            throw new \Exception('Request Url cannot be empty');
        }

        $this->url = $url;
    }

    abstract public function exec();
}