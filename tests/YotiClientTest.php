<?php
use Yoti\YotiClient;


define('PEM_FILE', __DIR__ . '/../src/sample-data/yw-access-security.pem');
//define('APP_ID', 'e5eca4a1-f9fc-42dd-b986-b23c96848ace');
define('SDK_ID', '990a3996-5762-4e8a-aa64-cb406fdb0e68');
define('YOTI_CONNECT_TOKEN', file_get_contents(__DIR__ . '/../src/sample-data/connect-token.txt'));
define('INVALID_YOTI_CONNECT_TOKEN', 'sdfsdfsdasdajsopifajsd=');

class YotiClientTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var YotiClient
     */
    private $_yoti;

    public function setUp()
    {
        $this->_yoti = new YotiClient(SDK_ID, file_get_contents(PEM_FILE));

        // switch this off when using real endpoints
        $this->_yoti->setMockRequests(true);
    }

    /**
     * test using pem file path
     */
    public function testPemFile()
    {
        new YotiClient(SDK_ID, 'file://' . PEM_FILE);
    }

    /**
     * test passing invalid pem filepath
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
        $ad = $this->_yoti->getActivityDetails(YOTI_CONNECT_TOKEN);
        $this->assertInstanceOf(\Yoti\ActivityDetails::class, $ad);
    }

    /**
     * Test invalid Token
     */
    public function testInvalidConnectToken()
    {
        $this->expectException('Exception');
        new YotiClient(SDK_ID, 'file://blahblah.pem');
        $this->_yoti->getActivityDetails(INVALID_YOTI_CONNECT_TOKEN);
    }

    public function testWrongYotiHeaderValue()
    {
        $this->expectException('Exception');
        $yotiClientObj = new YotiClient(SDK_ID, file_get_contents(PEM_FILE), YotiClient::DEFAULT_CONNECT_API, 'TEST');
    }

    public function testYotiHeaderValue()
    {
        $yotiClientObj = new YotiClient(SDK_ID, file_get_contents(PEM_FILE), YotiClient::DEFAULT_CONNECT_API, 'Wordpress');
        $this->assertInstanceOf(YotiClient::class, $yotiClientObj);
    }
}