<?php

// Make sure you run composer update inside the example folder before trying this example out
require_once './vendor/autoload.php';

use Yoti\Helper\ActivityDetailsHelper;

// Log any error message
$errorMsg = '';
// Selfie file name.
$selfieFile = 'selfie.jpeg';
// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';

$config = [
    'sdkId' => 'add your SDK ID here', // This is your SDK ID associated with the Yoti Application you created on Dashboard
    'pemFile' => __DIR__ . '/keys/your-key-name.pem', // This is the private key (in .pem format) associated with the Yoti Application you created on Dashboard
];

try {
    $yotiClient = new Yoti\YotiClient($config['sdkId'], $config['pemFile']);
    $profile = $yotiClient->getActivityDetails($token);
    $base64Selfie = ActivityDetailsHelper::getBase64Selfie($profile);
    $selfieEntity = $profile->getSelfieEntity();
    // Retrieve selfie image format
    $imageFormat = $selfieEntity ? $selfieEntity->getType() : 'jpeg';
    $selfieFile = "selfie.{$imageFormat}";
    // Create selfie image file.
    file_put_contents($selfieFile, $profile->getSelfie(), LOCK_EX);
} catch(\Exception $e) {
    $errorMsg = "Error - {$e->getMessage()}";
}
?>
<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <title>YOTI PROFILE</title>

        <link rel="stylesheet" type="text/css" href="css/style.css">
   </head>
   <body>
        <?php if (!empty($errorMsg)) : ?>
            <p><strong><?php echo $errorMsg ?></strong></p>
        <?php else: ?>
            <h1>Yoti User Profile</h1>
            <dl>
                <dt>Given Name(s)</dt>
                <dd><?php echo $profile->getGivenNames() ?></dd>

                <dt>Family Name</dt>
                <dd><?php echo $profile->getFamilyName() ?></dd>

                <dt>Phone</dt>
                <dd><?php echo $profile->getPhoneNumber() ?></dd>

                <dt>Email</dt>
                <dd><?php echo $profile->getEmailAddress() ?></dd>

                <dt>Date Of Birth</dt>
                <dd><?php echo $profile->getDateOfBirth() ?></dd>

                <dt>Address</dt>
                <dd><?php echo $profile->getPostalAddress() ?></dd>

                <dt>Gender</dt>
                <dd><?php echo $profile->getGender() ?></dd>

                <dt>Nationality</dt>
                <dd><?php echo $profile->getNationality() ?></dd>

                <dt>Selfie as base64 data</dt>
                <dd><img src="<?php echo $base64Selfie ?>" /></dd>

                <dt>Selfie as image file</dt>
                <dd><img src="./<?php echo $selfieFile ?>" /></dd>
            </dl>
        <?php endif; ?>
   </body>
 </html>
