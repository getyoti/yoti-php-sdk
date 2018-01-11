<?php

// Make sure you run composer update inside the example folder before trying this example out
require_once './vendor/autoload.php';
// Log any error message
$errorMsg = '';
// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';

$config = [
    'sdkId' => 'add your SDK ID here', // This is your SDK ID associated with the Yoti Application you created on Dashboard
    'pemFile' => __DIR__ . '/keys/your-key-name.pem', // This is the private key (in .pem format) associated with the Yoti Application you created on Dashboard
];

try {
    $yotiClient = new Yoti\YotiClient($config['sdkId'], $config['pemFile']);
    $profile = $yotiClient->getActivityDetails($token);
    $selfie = base64_encode($profile->getSelfie());
} catch(\Exception $e) {
    $errorMsg = "Error - {$e->getMessage()}";
}
?>
<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <title>PROFILE PAGE</title>
   </head>
   <body>
        <?php if (!empty($errorMsg)) : ?>
            <p><strong><?php echo $errorMsg; ?></strong></p>
        <?php else: ?>
            <strong>Given Name(s)</strong> <?php echo $profile->getGivenNames(); ?><br>
            <strong>Family Name</strong> <?php echo $profile->getFamilyName(); ?><br>
            <strong>Phone</strong> <?php echo $profile->getPhoneNumber(); ?><br>
            <strong>Email</strong> <?php echo $profile->getEmailAddress(); ?><br>
            <strong>Date Of Birth</strong> <?php echo $profile->getDateOfBirth(); ?><br>
            <strong>Address</strong> <?php echo $profile->getPostalAddress(); ?><br>
            <strong>Gender</strong> <?php echo $profile->getGender(); ?><br>
            <strong>Nationality</strong> <?php echo $profile->getNationality(); ?><br>
            <strong>Photo</strong><br> <img src="data:image/x-icon;base64,<?php echo $selfie ?>" />
            <br>
        <?php endif; ?>
   </body>
 </html>
