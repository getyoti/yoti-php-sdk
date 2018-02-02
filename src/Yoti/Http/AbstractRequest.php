<?php
namespace Yoti\Http;

abstract class AbstractRequest
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    /**
     * Methods that are sending payload.
     */
    const METHODS_WITH_PAYLOAD = 'POST, PUT, PATCH';

    // Error code
    const SUCCESSFUL_REQUEST = 200;
    const BAD_REQUEST_ERROR = 400;
    const UNAUTHORIZED_ERROR = 401;
    const INTERNAL_SERVER_ERROR = 500;
    const SERVICE_UNAVAILABLE_ERROR = 503;

    /**
     * @var string
     */
    protected $url;
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var string
     */
    protected $httpMethod;
    /**
     * @var \Yoti\Http\Payload
     */
    protected $payload;

    /**
     * AbstractRequest constructor.
     *
     * @param array $headers
     * @param $url
     * @param Payload $payload
     * @param string $httpMethod
     *
     * @throws \Exception
     */
    public function __construct(array $headers, $url, Payload $payload, $httpMethod = 'GET')
    {
        $this->setHttpMethod($httpMethod);
        $this->setHeaders($headers);
        $this->setUrl($url);
        $this->payload = $payload;
    }

    /**
     * @param array $headers
     * @throws \Exception
     */
    public function setHeaders(array $headers)
    {
        if(empty($headers)) {
            throw new \Exception('Request headers cannot be empty', 400);
        }

        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $url
     * @throws \Exception
     */
    public function setUrl($url)
    {
        if(empty($url)) {
            throw new \Exception('Request Url cannot be empty', 400);
        }

        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $httpMethod
     * @throws \Exception
     */
    public function setHttpMethod($httpMethod)
    {
        self::checkHttpMethod($httpMethod);

        $this->httpMethod = $httpMethod;
    }

    /**
     * @return mixed
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Check http method is valid.
     *
     * @param $httpMethod
     * @throws \Exception
     */
    public static function checkHttpMethod($httpMethod)
    {
        if(empty($httpMethod) || !self::isAllowed($httpMethod)) {
            throw new \Exception("Invalid http method {$httpMethod}", 400);
        }
    }

    /**
     * @param Payload $payload
     */
    public function setPayload(Payload $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return Payload
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Check if the method is sending payload data.
     *
     * @param $httpMethod
     * @return bool
     */
    public static function methodCanSendPayload($httpMethod)
    {
        $methodsWithPayload = explode(',', self::METHODS_WITH_PAYLOAD);
        return in_array($httpMethod, $methodsWithPayload, TRUE);
    }

    /**
     * Check the http method is allowed.
     *
     * @param $httpMethod
     *
     * @return bool
     */
    public static function isAllowed($httpMethod)
    {
        $allowedMethods = [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
            self::METHOD_DELETE,
        ];

        return in_array($httpMethod, $allowedMethods, TRUE);
    }

    abstract public function exec();
}