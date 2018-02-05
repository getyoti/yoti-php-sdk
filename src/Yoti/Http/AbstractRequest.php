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
     * You can add more method to this list separated by comma ','
     */
    const METHODS_THAT_INCLUDE_PAYLOAD = 'POST,PUT,PATCH';

    // Http request error code
    const SUCCESSFUL_REQUEST = 200;
    const BAD_REQUEST_ERROR = 400;
    const UNAUTHORIZED_ERROR = 401;
    const INTERNAL_SERVER_ERROR = 500;
    const SERVICE_UNAVAILABLE_ERROR = 503;

    /**
     * API url.
     *
     * @var string
     */
    protected $url;
    /**
     * @var array
     */
    protected $httpHeaders;
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
     * @param array $httpHeaders
     * @param string $url
     * @param Payload $payload
     * @param string $httpMethod
     *
     * @throws \Exception
     */
    public function __construct(array $httpHeaders, $url, Payload $payload, $httpMethod = 'GET')
    {
        $this->payload = $payload;

        $this->setUrl($url);
        $this->setHttpHeaders($httpHeaders);
        $this->setHttpMethod($httpMethod);
    }

    /**
     * @param array $headers
     *
     * @throws \Exception
     */
    public function setHttpHeaders(array $headers)
    {
        if(empty($headers)) {
            throw new \Exception('Request httpHeaders cannot be empty', 400);
        }

        $this->httpHeaders = $headers;
    }

    /**
     * @return mixed
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     * @param string $url
     *
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
     * @param string $httpMethod
     *
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
     * Check the provided http method is valid.
     *
     * @param string $httpMethod
     *
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
     * Check if the method can include payload data.
     *
     * @param string $httpMethod
     *
     * @return bool
     */
    public static function methodCanSendPayload($httpMethod)
    {
        $methodsThatIncludePayload = explode(',', self::METHODS_THAT_INCLUDE_PAYLOAD);
        return in_array($httpMethod, $methodsThatIncludePayload, TRUE);
    }

    /**
     * Check the http method is allowed.
     *
     * @param string $httpMethod
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