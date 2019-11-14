<?php

// Load dependent packages and env data

use Yoti\Entity\Attribute;
use Yoti\Entity\Profile;

require_once __DIR__ . '/../bootstrap.php';

// Get the token
$token = isset($_GET['token']) ? $_GET['token'] : '';
$profileAttributes = [];

try {
    $yotiClient = new Yoti\YotiClient(YOTI_SDK_ID, YOTI_KEY_FILE_PATH);
    $activityDetails = $yotiClient->getActivityDetails($token);
    $profile = $activityDetails->getProfile();

    $profileAttributes = [];
    foreach ($profile->getAttributesList() as $attribute) {
        switch ($attribute->getName()) {
            case Profile::ATTR_SELFIE:
            case Profile::ATTR_FULL_NAME:
                // Selfie and full name are handled separately.
                break;
            case Profile::ATTR_GIVEN_NAMES:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Given names', 'yoti-icon-profile');
                break;
            case Profile::ATTR_FAMILY_NAME:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Family names', 'yoti-icon-profile');
                break;
            case Profile::ATTR_DATE_OF_BIRTH:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Date of Birth', 'yoti-icon-calendar');
                break;
            case Profile::ATTR_GENDER:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Gender', 'yoti-icon-gender');
                break;
            case Profile::ATTR_STRUCTURED_POSTAL_ADDRESS:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Structured Postal Address', 'yoti-icon-address');
                break;
            case Profile::ATTR_POSTAL_ADDRESS:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Address', 'yoti-icon-address');
                break;
            case Profile::ATTR_PHONE_NUMBER:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Mobile number', 'yoti-icon-phone');
                break;
            case Profile::ATTR_NATIONALITY:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Nationality', 'yoti-icon-nationality');
                break;
            case Profile::ATTR_EMAIL_ADDRESS:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Email address', 'yoti-icon-email');
                break;
            case Profile::ATTR_DOCUMENT_DETAILS:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Document Details', 'yoti-icon-profile');
                break;
            case Profile::ATTR_DOCUMENT_IMAGES:
                $profileAttributes[] = createAttributeDisplayItem($attribute, 'Document Images', 'yoti-icon-profile');
                break;
            default:
                $profileAttributes[] = createAttributeDisplayItem(
                    $attribute,
                    ucwords(str_replace('_', ' ', $attribute->getName())),
                    'yoti-icon-profile'
                );
        }
    }

    // Add age verifications.
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
} catch (\Exception $e) {
    header('Location: /error.php?msg=' . $e->getMessage());
    exit;
}

/**
 * @param  \Yoti\Entity\Attribute $attribute
 * @param string $displayName
 * @param string $iconClass
 *
 * @return array
 */
function createAttributeDisplayItem(Attribute $attribute, $displayName, $iconClass) {
    return [
        'name' => $displayName,
        'obj' => $attribute,
        'icon' => $iconClass,
    ];
}
