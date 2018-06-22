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

    $givenNames     = $profile->getGivenNames();
    $familyName     = $profile->getFamilyName();
    $fullName       = $profile->getFullName();
    $phoneNumber    = $profile->getPhoneNumber();
    $emailAddress   = $profile->getEmailAddress();
    $dateOfBirth    = $profile->getDateOfBirth();
    $ageCondition   = $profile->getAgeCondition();
    $verifiedAge    = $profile->getVerifiedAge();
    $postalAddress  = $profile->getPostalAddress();
    $gender         = $profile->getGender();
    $nationality    = $profile->getNationality();
    $selfie         = $profile->getSelfie();

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
                <table class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th rowspan="2" scope="col">Name</th>
                            <th rowspan="2" scope="col">Value</th>
                            <th colspan="3" width="33%" class="table-dark">
                                <table class="table table-dark anchor-table">
                                    <tr class="row no-gutters">
                                        <td colspan="3" class="table-dark" align="center" width="100%">
                                            Anchors
                                        </td>
                                    </tr>
                                    <tr class="row no-gutters">
                                        <th class="col-md-3">S/V</th>
                                        <th class="col-md-6">Value</th>
                                        <th class="col-md-3">Subtype</th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($givenNames): ?>
                            <tr>
                                <th scope="row">Given Name(s)</th>
                                <td><?php echo $givenNames->getValue() ?></td>
                                <td colspan="3" width="33%">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($givenNames->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($givenNames->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($familyName) : ?>
                            <tr>
                                <th scope="row">Family Name</th>
                                <td><?php echo $familyName->getValue() ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($familyName->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($familyName->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($fullName) : ?>
                            <tr>
                                <th scope="row">Full Name</th>
                                <td><?php echo $fullName->getValue() ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($fullName->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($fullName->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($phoneNumber) : ?>
                            <tr>
                                <th scope="row">Phone</th>
                                <td><?php echo $phoneNumber->getValue() ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($phoneNumber->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($phoneNumber->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($emailAddress) : ?>
                            <tr>
                                <th scope="row">Email</th>
                                <td><?php echo $emailAddress->getValue() ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($emailAddress->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($emailAddress->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($dateOfBirth) : ?>
                            <tr>
                                <th scope="row">Date Of Birth</th>
                                <td><?php echo $dateOfBirth->getValue() ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($dateOfBirth->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($dateOfBirth->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($ageCondition) : ?>
                            <tr>
                                <th scope="row">Age verified</th>
                                <td><?php echo "{$verifiedAge} {$ageCheck}" ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($ageCondition->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($ageCondition->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($postalAddress) : ?>
                            <tr>
                                <th scope="row">Address</th>
                                <td>
                                    <p class="col-12 col-md-4">
                                        <?php echo $postalAddress->getValue() ?>
                                    </p>
                                </td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($postalAddress->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($postalAddress->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($gender) : ?>
                            <tr>
                                <th scope="row">Gender</th>
                                <td><?php echo $gender->getValue() ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($gender->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($gender->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($nationality) : ?>
                            <tr>
                                <th scope="row">Nationality</th>
                                <td><?php echo $nationality->getValue() ?></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($nationality->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($nationality->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($selfie) : ?>
                            <tr>
                                <th scope="row">Selfie as base64 data</th>
                                <td><img src="<?php echo $base64Selfie ?>" class="rounded" /></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($selfie->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($selfie->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Selfie as image file</th>
                                <td><img src="./selfie.jpeg" class="rounded" /></td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($selfie->getSources() as $source): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Source</td>
                                                    <td class="col-md-6"><?php echo $source->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $source->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach($selfie->getVerifiers() as $verifier): ?>
                                                <tr class="row no-gutters">
                                                    <td class="col-md-3">Verifier</td>
                                                    <td class="col-md-6"><?php echo $verifier->getValue() ?></td>
                                                    <td class="col-md-3"><?php echo $verifier->getSubType() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="table-dark">Name</td>
                            <td class="table-dark">Value</td>
                            <td colspan="3" width="33%" class="table-dark">
                                <table class="table table-dark anchor-table">
                                    <tr class="row no-gutters">
                                        <td class="col-md-3 table-dark">S/V</td>
                                        <td class="col-md-6 table-dark">Value</td>
                                        <td class="col-md-3 table-dark">Subtype</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
       </div>
   </body>
 </html>
