<?php
# AML check for outside the USA

// Load dependent packages and env data
require_once __DIR__ . '/bootstrap.php';

use Yoti\Aml\Address;
use Yoti\Aml\Country;
use Yoti\Aml\Profile;
use Yoti\YotiClient;


try {
  $amlAddress = new Address(new Country('GBR'));
  $amlProfile = new Profile('Edward Richard George', 'Heath', $amlAddress);
  $yotiClient = new YotiClient(getenv('YOTI_SDK_ID'), getenv('YOTI_KEY_FILE_PATH'));
  $amlResult = $yotiClient->performAmlCheck($amlProfile);
} catch(\Exception $e) {
  die("Error - {$e->getMessage()}");
}

var_dump($amlResult->isOnPepList());
var_dump($amlResult->isOnFraudList());
var_dump($amlResult->isOnWatchList());

echo 'Full result '. PHP_EOL;
echo $amlResult;



