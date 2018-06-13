<?php

// Load dependent packages and env data
require_once __DIR__ . '/bootstrap.php';

use Yoti\Helper\ActivityDetailsHelper;
use Yoti\Helper\ProfileHelper;

// Log any error message
$errorMsg = '';
// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';

try {
    $yotiClient = new Yoti\YotiClient(getenv('YOTI_SDK_ID'), getenv('YOTI_KEY_FILE_PATH'));
    $activityDetails = $yotiClient->getActivityDetails($token);
    $profile = $activityDetails->getProfile();
    // Create a base 64 selfie URI to be embedded in an HTML document
    $base64Selfie = ActivityDetailsHelper::getBase64Selfie($activityDetails);
    // Generated based on the dashboard attribute Age / Verify Condition
    // This function returns a boolean or NULL if the attribute is not set in the dashboard
    $ageVerified = $profile->getAgeCondition();
    $ageCheck = 'N/A';
    if(NULL !== $ageVerified) {
        $ageCheck = $ageVerified ? 'yes' : 'no';
    }
    $verifiedAge = $profile->getVerifiedAge();
    $verifiedAge = !empty($verifiedAge) ? "({$verifiedAge}) :" : '';
    // Create selfie image file.
    file_put_contents('selfie.jpeg', $profile->getSelfie()->getValue(), LOCK_EX);
} catch(\Exception $e) {
    $errorMsg = "Error - {$e->getMessage()}";
}
?>
<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8">
       <title>Yoti Profile</title>

       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
       <link rel="stylesheet" type="text/css" href="css/style.css">
   </head>
   <body>
        <h2><a href="/">Home</a></h2>
        <h2>Yoti User Profile</h2>

        <?php if (!empty($errorMsg)) : ?>
            <div class="alert alert-warning" role="alert">
                <p><strong><?php echo $errorMsg ?></strong></p>
            </div>
        <?php else: ?>
            <table class="table table-sm table-bordered table-hover">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Value</th>
                    <th scope="col">Sources</th>
                    <th scope="col">Verifiers</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Given Name(s)</th>
                        <td><?php echo $profile->getGivenNames()->getValue() ?></td>
                        <td>
                            <?php echo ProfileHelper::getAttributeSources($profile->getGivenNames()); ?>
                        </td>
                        <td>
                            <?php echo ProfileHelper::getAttributeVerifiers($profile->getGivenNames()) ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Family Name</th>
                        <td><?php echo $profile->getFamilyName()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getFamilyName()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getFamilyName()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Full Name</th>
                        <td><?php echo $profile->getFullName()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getFullName()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getFullName()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Phone</th>
                        <td><?php echo $profile->getPhoneNumber()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getPhoneNumber()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getPhoneNumber()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td><?php echo $profile->getEmailAddress()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getEmailAddress()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getEmailAddress()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date Of Birth</th>
                        <td><?php echo $profile->getDateOfBirth()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getDateOfBirth()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getDateOfBirth()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Age verified</th>
                        <td><?php echo "{$verifiedAge} {$ageCheck}" ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getAgeCondition()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getAgeCondition()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address</th>
                        <td><?php echo $profile->getPostalAddress()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getPostalAddress()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getPostalAddress()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Gender</th>
                        <td><?php echo $profile->getGender()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getGender()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getGender()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Nationality</th>
                        <td><?php echo $profile->getNationality()->getValue() ?></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getNationality()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getNationality()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Selfie as base64 data</th>
                        <td><img src="<?php echo $base64Selfie ?>" class="rounded" /></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getSelfie()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getSelfie()) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Selfie as image file</th>
                        <td><img src="./selfie.jpeg" class="rounded" /></td>
                        <td><?php echo ProfileHelper::getAttributeSources($profile->getSelfie()) ?></td>
                        <td><?php echo ProfileHelper::getAttributeVerifiers($profile->getSelfie()) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
   </body>
 </html>
