<?php

namespace YotiTest\Http;

use Yoti\YotiClient;
use Yoti\Http\CurlRequestHandler;
use Yoti\Http\RequestSigner;
use YotiTest\TestCase;
use Yoti\Http\Payload;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;

class CurlRequestHandlerTest extends TestCase
{
    public $pem;
    /**
     * @var CurlRequestHandler
     */
    public $requestHandler;
    /**
     * @var Payload
     */
    public $payload;

    public $requestSigner;

    public $postMethod = CurlRequestHandler::METHOD_POST;

    public function setup()
    {
        $endpoint = '/aml-check';
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $this->payload = new Payload($amlProfile->getData());
        $this->postMethod = CurlRequestHandler::METHOD_POST;

        $this->pem = file_get_contents(PEM_FILE);

        $this->requestSigner = new RequestSigner(
            $this->requestHandler,
            $endpoint,
            'POST'
        );

        $this->requestHandler = new CurlRequestHandler(
            YotiClient::DEFAULT_CONNECT_API,
            $this->pem,
            SDK_ID,
            'PHP'
        );
    }

    public function testGetPem()
    {
        $this->assertEquals($this->pem, $this->requestHandler->getPem());
    }

    public function testGetSdkId()
    {
        $this->assertEquals(SDK_ID, $this->requestHandler->getSdkId());
    }

    public function testInvalidHttpMethod()
    {
        $this->expectException('\Yoti\Exception\RequestException');
        $this->requestHandler->sendRequest(
            '/aml-check',
            'InvalidHttpMethod',
            $this->payload
        );
    }

    public function testSendRequestExists()
    {
        $this->assertTrue(method_exists($this->requestHandler, 'sendRequest'));
    }

    public function testGenerateRequestHeaders()
    {
        $signedData = RequestSigner::signRequest(
            $this->requestHandler,
            '/aml-check',
            'POST',
            $this->payload
        );
        $signedMessage = $signedData[RequestSigner::SIGNED_MESSAGE_KEY];
        $requestHeaders = $this->invokeMethod(
            $this->requestHandler,
            'generateRequestHeaders',
            [$signedMessage]
        );

        $this->assertEquals(
            json_encode($this->getHeaders($signedMessage)),
            json_encode($requestHeaders)
        );
    }

    public function getHeaders($signedMessage)
    {
        $authKey = $this->getAuthKey();

        $headers = [
            CurlRequestHandler::YOTI_AUTH_HEADER_KEY . ": {$authKey}",
            CurlRequestHandler::YOTI_DIGEST_HEADER_KEY . ": {$signedMessage}",
            CurlRequestHandler::YOTI_SDK_IDENTIFIER_KEY . ": PHP",
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        return $headers;
    }

    /**
     * Extract Auth key content to be included in the header.
     *
     * @return string
     */
    public function getAuthKey()
    {
        $pemContent = file_get_contents(PEM_FILE);

        $details = openssl_pkey_get_details(openssl_pkey_get_private($pemContent));
        if (!array_key_exists('key', $details)) {
            return NULL;
        }

        // Remove BEGIN RSA PRIVATE KEY / END RSA PRIVATE KEY lines
        $key = trim($details['key']);
        // Support line break on *nix systems, OS, older OS, and Microsoft
        $_key = preg_split('/\r\n|\r|\n/', $key);
        if (strpos($key, 'BEGIN') !== FALSE) {
            array_shift($_key);
            array_pop($_key);
        }
        $key = implode('', $_key);

        // Check auth key is not empty
        if (empty($key)) {
            throw new RequestException('Could not retrieve key from PEM.', 401);
        }

        return $key;
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}