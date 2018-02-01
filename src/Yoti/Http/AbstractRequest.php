<?php
namespace Yoti\Http;

abstract class AbstractRequest
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    protected $headers;
    protected $url;

    public function __construct(array $headers, $url)
    {
        $this->setHeaders($headers);
        $this->setUrl($url);
    }

    public function setHeaders(array $headers)
    {
        if(empty($headers)) {
            throw new \Exception('Request headers cannot be empty', 400);
        }

        $this->headers = $headers;
    }

    public function setUrl($url)
    {
        if(empty($url)) {
            throw new \Exception('Request Url cannot be empty', 400);
        }

        $this->url = $url;
    }

    abstract public function exec();
}