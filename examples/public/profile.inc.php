<?php

// Load dependent packages and env data
require_once __DIR__ . '/../bootstrap.php';

// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';
$profileAttributes = [];

try {
    $yotiClient = new Yoti\YotiClient(YOTI_SDK_ID, YOTI_KEY_FILE_PATH);
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
        ],
        [
            'name' => 'Structured Postal Address',
            'obj' => $profile->getStructuredPostalAddress(),
            'icon' => 'yoti-icon-profile',
        ],
        [
            'name' => 'Document Details',
            'obj' => $profile->getDocumentDetails(),
            'icon' => 'yoti-icon-profile',
        ],
        [
            'name' => 'Document Images',
            'obj' => $profile->getDocumentImages(),
            'icon' => 'yoti-icon-profile',
        ]
    ];

    $ageVerifications = $profile->getAgeVerifications();
    if ($ageVerifications) {
        foreach ($ageVerifications as $ageVerification) {
            $profileAttributes[] = [
                'name' => 'Age Verification',
                'obj' => $ageVerification->getAttribute(),
                'age_verification' => $ageVerification,
                'icon' => 'yoti-icon-profile',
            ];
        }
    }

    $fullName = $profile->getFullName();
} catch(\Exception $e) {
    header('Location: /error.php?msg='.$e->getMessage());
    exit;
}