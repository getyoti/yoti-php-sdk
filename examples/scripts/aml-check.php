<?php
# AML check for outside the USA

// Load dependent packages and env data
require_once __DIR__ . '/../bootstrap.php';

use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\YotiClient;


try {
  $amlAddress = new AmlAddress(new Country('GBR'));
  $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
  $yotiClient = new YotiClient(YOTI_SDK_ID, YOTI_KEY_FILE_PATH);
  $amlResult = $yotiClient->performAmlCheck($amlProfile);
} catch(\Exception $e) {
  die("Error - {$e->getMessage()}");
}

var_dump($amlResult->isOnPepList());
var_dump($amlResult->isOnFraudList());
var_dump($amlResult->isOnWatchList());

echo 'Full result '. PHP_EOL;
echo $amlResult;



