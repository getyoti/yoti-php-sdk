<?php
# AML check for USA (requires postcode and SSN)

// Load dependent packages and env data
require_once __DIR__ . '/bootstrap.php';

use Yoti\Aml\Address;
use Yoti\Aml\Country;
use Yoti\Aml\Profile;
use Yoti\YotiClient;

$zipCode = '12345';
$ssn = '170206773';

try {
    $amlAddress = new Address(new Country('USA'), $zipCode);
    $amlProfile = new Profile('Edward Richard George', 'Heath', $amlAddress, $ssn);
    $yotiClient = new YotiClient(getenv('YOTI_SDK_ID'), getenv('YOTI_KEY_FILE_PATH'));
    $amlResult = $yotiClient->performAmlCheck($amlProfile);
} catch(\Exception $e) {
    die("Error - {$e->getMessage()}");
}

var_dump($amlResult->isOnPepList());
var_dump($amlResult->isOnFraudList());
var_dump($amlResult->isOnWatchList());

echo 'Full result'. PHP_EOL;
echo $amlResult;