<?php
require_once __DIR__ . '/../src/boot.php';

// token should be receieved from yoti server.
$encryptedYotiToken = file_get_contents(__DIR__.'/../src/sample-data/connect-token.txt');

$config = [
    'sdkId' => 'stub-app-id',
    'pemFile' => __DIR__ . '/../src/sample-data/node-sdk-test.pem',
];
$client = new Yoti\YotiClient($config['sdkId'], $config['pemFile']);
$client->setMockRequests(true);
$profile = $client->getActivityDetails($encryptedYotiToken);

// output all profile attributes
var_dump($profile->getProfileAttribute());
