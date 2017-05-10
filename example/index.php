<?php
require_once __DIR__ . '/../src/boot.php';

// token should be receieved from yoti server.
$encryptedYotiToken = file_get_contents(__DIR__ . '/../src/sample-data/connect-token.txt');
//$encryptedYotiToken ="k7pQhQq4FOrK-7EagyMlKgFS-un68M3IfAO_pjm3Q3fK3fUaF7bgulrTNSetwP7IIUU7BrkU5v9HQZyG7IKAjkxGCfw7zrqeDkGfO0GT81o1N_hWNWL-7KhEBewIfyFpUpDH_Q41Xj6n9-EbhPdCGe0pnzUYaUaVsS8S0EpmcbwnMN9IQbN_RnrrmHFACKRN66Y3sTmq6gtfptBtoaz6Mx_PQZUQJGNiKXg5juoBhjZWMS7ypxUoZLvmvd-Ph_mnJ1uOqsetZLUg0iC_qQQsy2mFjrnJBD6QtkiFmhtSSmeYI5c7C3OClYkG4sBuZOy46cR4Wj06v7dPc1DxpyKYfw==";

$encryptedYotiToken = "MJtM8JHt27_cJPL9OoOURInyxzBNZVtvfyIolUaTL8hg2LULnjNSUdhhrzBt86o1QdaH0RGZZ_GVEUB_ZUPKHoe2aBugfGjI62PA9g4o_9y4Ie4CXiGMP2uUb1v_6FDp28yZHiHAC4ib8O97_qfBC5gLUQCmBtc8C-qL9H7H71Hp_PE_uDfveM6grfwfA9bIBLpGTjVRVWVk3Hl4kwkCxtRHh2vh7kRImRYcXBDjd8T0R_jAvYfrUAom7HX9aOprD0SzMOPpF78TBiqD_iWZAm1QZvq_tq_3mVq2Oi656V38iGCpv2oCHtJBJwS0g0upY3tQclLt8Y_RcGdfYWBkMw==";

$config = [
//    'sdkId' => '89ec075d-0b9d-4127-96c2-96ca3fbe00a5',
//    'pemFile' => __DIR__ . '/keys/PHP SDK - Local-access-security.pem',
    'sdkId' => '6008c4e7-24f4-4866-b2fa-5db9bf59fc5b',
    'pemFile' => __DIR__ . '/keys/The Greatest Drupal 7 Plugin On Gods Green Earth-access-security.pem',
];
$client = new Yoti\YotiClient($config['sdkId'], $config['pemFile']);
//$client->setMockRequests(true);
$profile = $client->getActivityDetails($encryptedYotiToken);

// output all profile attributes
var_dump($profile->getProfileAttribute());
