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

    $givenNames   = $profile->getGivenNames();
    $familyName   = $profile->getFamilyName();
    $fullName     = $profile->getFullName();
    $phoneNumber  = $profile->getPhoneNumber();
    $emailAddress = $profile->getEmailAddress();
    $dateOfBirth  = $profile->getDateOfBirth();
    $ageCondition = $profile->getAgeCondition();
    $verifiedAge  = $profile->getVerifiedAge();
    $address      = $profile->getPostalAddress();
    $gender       = $profile->getGender();
    $nationality  = $profile->getNationality();
    $selfie       = $profile->getSelfie();

    // Create a base 64 selfie URI to be embedded in an HTML document
    $base64Selfie = ActivityDetailsHelper::getBase64Selfie($activityDetails);
    // Generated based on the dashboard attribute Age / Verify Condition
    // This function returns a boolean or NULL if the attribute is not set in the dashboard
    $ageConditionValue = $ageCondition ? $ageCondition->getValue() : NULL;
    $ageCheck = 'N/A';
    if (NULL !== $ageConditionValue) {
        $ageCheck = $ageConditionValue ? 'yes' : 'no';
    }
    $verifiedAgeValue = $verifiedAge ? $verifiedAge->getValue() : NULL;
    $verifiedAge = NULL !== $verifiedAgeValue ? "({$verifiedAgeValue}) :" : '';

    // Create selfie image file.
    if ($selfie) {
        file_put_contents('selfie.jpeg', $selfie->getValue(), LOCK_EX);
    }
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
       <div class="container">
            <h2><a href="/">Home</a></h2>
            <h2>User Profile Page</h2>

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
                            <td><?php echo $givenNames ? $givenNames->getValue() : '' ?></td>
                            <td>
                                <?php echo ProfileHelper::getAttributeSources($givenNames) ?>
                            </td>
                            <td>
                                <?php echo ProfileHelper::getAttributeVerifiers($givenNames) ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Family Name</th>
                            <td><?php echo $familyName ? $familyName->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($familyName) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($familyName) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Full Name</th>
                            <td><?php echo $fullName ? $fullName->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($fullName) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($fullName) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone</th>
                            <td><?php echo $phoneNumber ? $phoneNumber->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($phoneNumber) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($phoneNumber) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?php echo $emailAddress ? $emailAddress->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($emailAddress) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($emailAddress) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Date Of Birth</th>
                            <td><?php echo $dateOfBirth ? $dateOfBirth->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($dateOfBirth) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($dateOfBirth) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Age verified</th>
                            <td><?php echo "{$verifiedAge} {$ageCheck}" ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($ageCondition) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($ageCondition) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Address</th>
                            <td><?php echo $address ? $address->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($address) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($address) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Gender</th>
                            <td><?php echo $gender ? $gender->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($gender) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($gender) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Nationality</th>
                            <td><?php echo $nationality ? $nationality->getValue() : '' ?></td>
                            <td><?php echo ProfileHelper::getAttributeSources($nationality) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($nationality) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Selfie as base64 data</th>
                            <td><img src="<?php echo $base64Selfie ?>" class="rounded" /></td>
                            <td><?php echo ProfileHelper::getAttributeSources($selfie) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($selfie) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Selfie as image file</th>
                            <td><img src="./selfie.jpeg" class="rounded" /></td>
                            <td><?php echo ProfileHelper::getAttributeSources($selfie) ?></td>
                            <td><?php echo ProfileHelper::getAttributeVerifiers($selfie) ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
       </div>
   </body>
 </html>
