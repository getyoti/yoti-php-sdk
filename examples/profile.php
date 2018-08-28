<?php

// Load dependent packages and env data
require_once __DIR__ . '/bootstrap.php';

// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';
$profileAttributes = [];

try {
    $yotiClient = new Yoti\YotiClient(getenv('YOTI_SDK_ID'), getenv('YOTI_KEY_FILE_PATH'));
    $activityDetails = $yotiClient->getActivityDetails($token);
    $profile = $activityDetails->getProfile();

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
    $selfieFileName = 'selfie.jpeg';

    // Create selfie image file.
    if ($selfie && is_writable(__DIR__)) {
        file_put_contents($selfieFileName, $selfie->getValue(), LOCK_EX);
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

       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
       <link rel="stylesheet" type="text/css" href="assets/css/profile.css">
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
                                   <div class="yoti-attribute-value-text"><?php echo $item['obj']->getValue() ?></div>
                               </div>
                               <div class="yoti-attribute-anchors-layout">
                                   <div class="yoti-attribute-anchors-head -s-v">S / V</div>
                                   <div class="yoti-attribute-anchors-head -value">Value</div>
                                   <div class="yoti-attribute-anchors-head -subtype">Sub type</div>

                                   <?php foreach($item['obj']->getSources() as $source) : ?>
                                       <div class="yoti-attribute-anchors -s-v">Source</div>
                                       <div class="yoti-attribute-anchors -value"><?php echo $source->getValue() ?></div>
                                       <div class="yoti-attribute-anchors -subtype"><?php echo $source->getSubType() ?></div>
                                   <?php endforeach; ?>

                                   <?php foreach($item['obj']->getVerifiers() as $verifier) : ?>
                                       <div class="yoti-attribute-anchors -s-v">Verifier</div>
                                       <div class="yoti-attribute-anchors -value"><?php echo $verifier->getValue() ?></div>
                                       <div class="yoti-attribute-anchors -subtype"><?php $verifier->getSubType() ?></div>
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
