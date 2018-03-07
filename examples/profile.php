<?php

// Load dependent packages and env data
require_once __DIR__ . '/bootstrap.php';

use Yoti\Helper\ActivityDetailsHelper;

// Log any error message
$errorMsg = '';
// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';

try {
    $yotiClient = new Yoti\YotiClient(getenv('YOTI_SDK_ID'), getenv('YOTI_KEY_FILE_PATH'));
    $profile = $yotiClient->getActivityDetails($token);
    // Create a base 64 selfie URI to be embedded in an HTML document
    $base64Selfie = ActivityDetailsHelper::getBase64Selfie($profile);
    // Generated based on the dashboard attribute Age / Verify Condition
    // This function returns boolean or NULLif the attribute is not set in the dashboard
    $ageVerified = var_export($profile->isAgeVerified(), TRUE);
    $ageVerified = NULL === $ageVerified ? '' : $ageVerified;
    $verifiedAge = $profile->getVerifiedAge();
    $verifiedAge = !empty($verifiedAge) ? "({$verifiedAge}) :" : '';
    // Create selfie image file.
    file_put_contents('selfie.jpeg', $profile->getSelfie(), LOCK_EX);
} catch(\Exception $e) {
    $errorMsg = "Error - {$e->getMessage()}";
}
?>
<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <title>Yoti Profile</title>

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

                <dt>Full Name</dt>
                <dd><?php echo $profile->getFullName() ?></dd>

                <dt>Phone</dt>
                <dd><?php echo $profile->getPhoneNumber() ?></dd>

                <dt>Email</dt>
                <dd><?php echo $profile->getEmailAddress() ?></dd>

                <dt>Date Of Birth</dt>
                <dd><?php echo $profile->getDateOfBirth() ?></dd>

                <dt>Age verified</dt>
                <dd><?php echo "{$verifiedAge} {$ageVerified}" ?></dd>

                <dt>Address</dt>
                <dd><?php echo $profile->getPostalAddress() ?></dd>

                <dt>Gender</dt>
                <dd><?php echo $profile->getGender() ?></dd>

                <dt>Nationality</dt>
                <dd><?php echo $profile->getNationality() ?></dd>

                <dt>Selfie as base64 data</dt>
                <dd><img src="<?php echo $base64Selfie ?>" /></dd>

                <dt>Selfie as image file</dt>
                <dd><img src="./selfie.jpeg" /></dd>
            </dl>
        <?php endif; ?>
   </body>
 </html>
