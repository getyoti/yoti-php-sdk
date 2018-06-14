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
            <h3>User Profile Page</h3>

            <?php if (!empty($errorMsg)) : ?>
                <div class="alert alert-warning" role="alert">
                    <p><strong><?php echo $errorMsg ?></strong></p>
                </div>
            <?php else: ?>
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr class="table-primary">
                            <th scope="col">Name</th>
                            <th scope="col">Value</th>
                            <th scope="col">Anchors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Given Name(s)</th>
                            <td><?php echo $givenNames ? $givenNames->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($givenNames): ?>
                                            <?php foreach($givenNames->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($givenNames->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Family Name</th>
                            <td><?php echo $familyName ? $familyName->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($familyName) : ?>
                                            <?php foreach($familyName->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($familyName->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Full Name</th>
                            <td><?php echo $fullName ? $fullName->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($fullName) : ?>
                                            <?php foreach($fullName->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($fullName->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Phone</th>
                            <td><?php echo $phoneNumber ? $phoneNumber->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($phoneNumber) : ?>
                                            <?php foreach($phoneNumber->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($phoneNumber->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?php echo $emailAddress ? $emailAddress->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($emailAddress) : ?>
                                            <?php foreach($emailAddress->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($emailAddress->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Date Of Birth</th>
                            <td><?php echo $dateOfBirth ? $dateOfBirth->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($dateOfBirth) : ?>
                                            <?php foreach($dateOfBirth->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($dateOfBirth->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Age verified</th>
                            <td><?php echo "{$verifiedAge} {$ageCheck}" ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($ageCondition) : ?>
                                            <?php foreach($ageCondition->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($ageCondition->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Address</th>
                            <td>
                                <address>
                                    <?php echo $address ? $address->getValue() : '' ?>
                                </address>
                            </td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($address) : ?>
                                            <?php foreach($address->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($address->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Gender</th>
                            <td><?php echo $gender ? $gender->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($gender) : ?>
                                            <?php foreach($gender->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($gender->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Nationality</th>
                            <td><?php echo $nationality ? $nationality->getValue() : '' ?></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($nationality) : ?>
                                            <?php foreach($nationality->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($nationality->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Selfie as base64 data</th>
                            <td><img src="<?php echo $base64Selfie ?>" class="rounded" /></td>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($selfie) : ?>
                                            <?php foreach($selfie->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($selfie->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Selfie as image file</th>
                            <td><img src="./selfie.jpeg" class="rounded" /></td>
                            <td>
                                <table class="table">
                                    <thead  class="thead-light">
                                        <tr>
                                            <th>S/V</th>
                                            <th>Value</th>
                                            <th>SubType</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($selfie) : ?>
                                            <?php foreach($selfie->getSources() as $source): ?>
                                                <tr>
                                                    <td>Source</td>
                                                    <td><?php echo $source->getValue() ?></td>
                                                    <td><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($selfie->getVerifiers() as $verifier): ?>
                                                <tr>
                                                    <td>Verifier</td>
                                                    <td><?php echo $verifier->getValue() ?></td>
                                                    <td><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
       </div>
   </body>
 </html>
