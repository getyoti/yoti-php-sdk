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

    $ageVerifications = $profile->getAgeVerifications();
    // Get the first AgeVerification element
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

    $ageVerificationStr = '';
    if ($ageVerification) {
        $result = $ageVerification->getResult() ? 'Yes' : 'No';
        $ageVerificationStr = "({$ageVerification->getChecktype()} {$ageVerification->getAge()}) : {$result}";
    }
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