<?php

// Load dependent packages and env data
require_once __DIR__ . '/bootstrap.php';

// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';
$profileAttributes = [];

try {
    $yotiConnectApi = getenv('YOTI_CONNECT_API') ?: Yoti\YotiClient::DEFAULT_CONNECT_API;
    $yotiClient = new Yoti\YotiClient(
        getenv('YOTI_SDK_ID'),
        getenv('YOTI_KEY_FILE_PATH'),
        $yotiConnectApi
    );
    $activityDetails = $yotiClient->getActivityDetails($token);
    $profile = $activityDetails->getProfile();
    $ageVerifications = $profile->getAgeVerifications();
    $ageVerification = current($ageVerifications);

    $profileAttributes = [
        [
            'name' => 'Given names',
            'obj' => $profile->getGivenNames(),
            'icon' => 'yoti-icon-profile',
        ],
        [
            'name' => 'Family names',
            'obj' => $profile->getFamilyName(),
            'icon' => 'yoti-icon-profile',
        ],
        [
            'name' => 'Mobile number',
            'obj' => $profile->getPhoneNumber(),
            'icon' => 'yoti-icon-phone',
        ],
        [
            'name' => 'Email address',
            'obj' => $profile->getEmailAddress(),
            'icon' => 'yoti-icon-email',
        ],
        [
            'name' => 'Date of birth',
            'obj' => $profile->getDateOfBirth(),
            'icon' => 'yoti-icon-calendar',
        ],
        [
            'name' => 'Age Verification',
            'obj' => $ageVerification,
            'icon' => 'yoti-icon-calendar',
        ],
        [
            'name' => 'Address',
            'obj' => $profile->getPostalAddress(),
            'icon' => 'yoti-icon-address',
        ],
        [
            'name' => 'Gender',
            'obj' => $profile->getGender(),
            'icon' => 'yoti-icon-gender',
        ],
        [
            'name' => 'Nationality',
            'obj' => $profile->getNationality(),
            'icon' => 'yoti-icon-nationality',
        ]
    ];

    $fullName = $profile->getFullName();
    $selfie = $profile->getSelfie();
    $selfieFileName = 'selfie.jpeg';

    // Create selfie image file.
    if ($selfie && is_writable(__DIR__)) {
        file_put_contents($selfieFileName, $selfie->getValue()->getContent(), LOCK_EX);
    }
} catch(\Exception $e) {
    header('Location: /error.php?msg='.$e->getMessage());
    exit;
}
?>
<!DOCTYPE html>
<html class="yoti-html">
   <head>
       <meta charset="utf-8">
       <title>Yoti client example</title>
       <link rel="stylesheet" type="text/css" href="assets/css/profile.css">
       <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" />
   </head>
   <body class="yoti-body">
       <main class="yoti-profile-layout">
           <section class="yoti-profile-user-section">

               <div class="yoti-profile-picture-powered-section">
                   <span class="yoti-profile-picture-powered">Powered by</span>
                   <a href="https://www.yoti.com" target="_blank">
                       <img class="yoti-logo-image" src="assets/images/logo.png" srcset="assets/images/logo@2x.png 2x" alt="Yoti" />
                   </a>
               </div>

               <div class="yoti-profile-picture-section">
                   <?php if ($profile->getSelfie()) : ?>
                   <div class="yoti-profile-picture-area">
                       <img src="./<?php echo $selfieFileName ?>" class="yoti-profile-picture-image" alt="Yoti" />
                       <i class="yoti-profile-picture-verified-icon"></i>
                   </div>
                   <?php endif; ?>

                   <div class="yoti-profile-name">
                       <?php echo $fullName? $fullName->getValue() : '' ?>
                   </div>
               </div>
           </section>

           <section class="yoti-attributes-section">
               <a href="/">
                   <img class="yoti-company-logo" src="assets/images/company-logo.jpg" alt="company logo">
               </a>

               <div class="yoti-attribute-list-header">
                   <div class="yoti-attribute-list-header-attribute">Attribute</div>
                   <div class="yoti-attribute-list-header-value">Value</div>
                   <div>Anchors</div>
               </div>

               <div class="yoti-attribute-list-subheader">
                   <div class="yoti-attribute-list-subhead-layout">
                       <div>S / V</div>
                       <div>Value</div>
                       <div>Sub type</div>
                   </div>
               </div>

               <div class="yoti-attribute-list">

                   <?php foreach($profileAttributes as $item): ?>
                       <?php if ($item['obj']) : ?>
                           <div class="yoti-attribute-list-item">
                               <div class="yoti-attribute-name">
                                   <div class="yoti-attribute-name-cell">
                                       <i class="<?php echo $item['icon'] ?>"></i>
                                       <span class="yoti-attribute-name-cell-text"><?php echo  $item['name'] ?></span>
                                   </div>
                               </div>

                               <div class="yoti-attribute-value">
                                   <?php
                                        $name = $item['name'];
                                        $attributeObj = $item['obj'];
                                        switch($name) {
                                            case 'Date of birth':
                                                $value = $item['obj']->getValue()->format('d-m-Y');
                                                break;
                                            case 'Age Verification':
                                                $attributeObj = $item['obj']->getAttribute();
                                                $result = $ageVerification->getResult() ? 'Yes' : 'No';
                                                $value = $ageVerification->getChecktype() . ':' . $ageVerification->getAge()
                                                    . ' ' . $result;
                                                break;
                                            default:
                                                $value = $item['obj']->getValue();
                                        }

                                        $anchors = $attributeObj->getAnchors();
                                   ?>
                                   <div class="yoti-attribute-value-text"><?php echo $value; ?></div>
                               </div>
                               <div class="yoti-attribute-anchors-layout">
                                   <div class="yoti-attribute-anchors-head -s-v">S / V</div>
                                   <div class="yoti-attribute-anchors-head -value">Value</div>
                                   <div class="yoti-attribute-anchors-head -subtype">Sub type</div>

                                   <?php foreach($anchors as $anchor) : ?>
                                       <div class="yoti-attribute-anchors -s-v"><?php echo $anchor->getType(); ?></div>
                                       <div class="yoti-attribute-anchors -value"><?php echo $anchor->getValue() ?></div>
                                       <div class="yoti-attribute-anchors -subtype"><?php echo $anchor->getSubType() ?></div>
                                   <?php endforeach; ?>

                               </div>
                           </div>
                       <?php endif; ?>
                   <?php endforeach; ?>
               </div>
           </section>
       </main>
   </body>
 </html>
