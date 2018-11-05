<?php

namespace YotiTest;

use Yoti\YotiClient;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\AmlResult;

class YotiClientTest extends TestCase
{
    /**
     * @var YotiClient
     */
    public $yotiClient;

    public $pem;

    public $amlProfile;

    public $amlResult = [];

    public function setUp()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $this->amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $this->pem = file_get_contents(PEM_FILE);

        $this->amlResult['response'] = file_get_contents(AML_CHECK_RESULT_JSON);
        $this->amlResult['http_code'] = 200;

        $this->yotiClient = $this->getMockBuilder('Yoti\YotiClient')
            ->setConstructorArgs([SDK_ID, $this->pem])
            ->setMethods(['makeRequest'])
            ->getMock();
    }

    /**
     * Test the use of pem file
     */
    public function testCanUsePemFile()
    {
        $yotiClientObj = new YotiClient(SDK_ID, 'file://' . PEM_FILE);
        $this->assertInstanceOf(\Yoti\YotiClient::class, $yotiClientObj);
    }

    /**
     * Test passing invalid pem file path
     */
    public function testInvalidPem()
    {
        $this->expectException('Exception');
        $yotiClientObj = new YotiClient(SDK_ID, 'file://blahblah.pem');
    }

    /**
     * Test getting activity details
     */
    public function testGetActivityDetails()
    {
        $result['response'] = file_get_contents(RECEIPT_JSON);
        $result['http_code'] = 200;

        // Stub the method makeRequest to return the result we want
        $this->yotiClient->method('makeRequest')
            ->willreturn($result);
        $ad = $this->yotiClient->getActivityDetails(YOTI_CONNECT_TOKEN);

        $this->assertInstanceOf(\Yoti\ActivityDetails::class, $ad);
    }

    /**
     * Test performAmlCheck with a mock result
     */
    public function testPerformAmlCheck()
    {
        $this->yotiClient->method('makeRequest')
            ->willReturn($this->amlResult);

        $result = $this->yotiClient->performAmlCheck($this->amlProfile);

        $this->assertInstanceOf(AmlResult::class, $result);
    }

    /**
     * Test invalid Token
     */
    public function testInvalidConnectToken()
    {
        $yotiClient = new YotiClient(SDK_ID, file_get_contents(PEM_FILE));

        $this->expectException('Exception');
        $yotiClient->getActivityDetails(INVALID_YOTI_CONNECT_TOKEN);
    }

    /**
     * Test invalid http header value for X-Yoti-SDK
     */
    public function testInvalidSdkIdentifier()
    {
        $this->expectException('Exception');
        $yotiClientObj = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            'WrongHeader'
        );
    }

    /**
     * Test X-Yoti-SDK http header value for Wordpress
     */
    public function testCanUseWordPressAsSdkIdentifier()
    {
        $expectedValue  = 'WordPress';
        $yotiClientObj  = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            $expectedValue
        );
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    /**
     * Test X-Yoti-SDK http header value for Drupal
     */
    public function testCanUseDrupalAsSdkIdentifier()
    {
        $expectedValue  = 'Drupal';
        $yotiClientObj  = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            $expectedValue
        );
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    /**
     * Test X-Yoti-SDK http header value for Joomla
     */
    public function testCanUseJoomlaAsSdkIdentifier()
    {
        $expectedValue  = 'Joomla';
        $yotiClientObj  = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            $expectedValue
        );
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    /**
     * Test X-Yoti-SDK http header value for PHP
     */
    public function testCanUsePHPAsSdkIdentifier()
    {
        $expectedValue  = 'PHP';
        $yotiClientObj  = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            $expectedValue
        );
        $property       = $this->getPrivateProperty('Yoti\YotiClient', '_sdkIdentifier');
        $this->assertEquals($property->getValue($yotiClientObj), $expectedValue);
    }

    public function testRequestHeadersShouldIncludeSDKVersion()
    {
        $yotiClientObj  = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            'PHP'
        );
        $requestHeaders = $this->invokeMethod(
            $yotiClientObj,
            'getRequestHeaders',
            ['fakeSignedMessage']
        );
        $headersStr = implode(',', $requestHeaders);
        $includeVersion = strpos($headersStr, YotiClient::YOTI_SDK_VERSION);
        $this->assertNotFalse($includeVersion);
    }

    /**
     * Get private or protected property of a class.
     *
     * @param $className
     * @param $propertyName
     *
     * @return \ReflectionProperty
     *
     * @throws \ReflectionException
     */
    public function getPrivateProperty($className, $propertyName)
    {
        $reflector = new \ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(TRUE);

        return $property;
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