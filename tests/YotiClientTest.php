<?php
use Yoti\YotiClient;

require_once __DIR__ . '/../src/boot.php';

define('PEM_FILE', __DIR__ . '/../src/sample-data/node-sdk-test.pem');
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
     * @expectedException Exception
     */
    public function testInvalidPem()
    {
        new YotiClient(SDK_ID, 'file://blahblah.pem');
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
     * @expectedException Exception
     */
    public function testInvalidConnectToken()
    {
        new YotiClient(SDK_ID, 'file://blahblah.pem');
        $this->_yoti->getActivityDetails(INVALID_YOTI_CONNECT_TOKEN);
    }
}