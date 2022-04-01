<?php

namespace App\Http\Controllers;

use Yoti\YotiClient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yoti\Profile\Attribute;
use Yoti\Profile\UserProfile;

class ProfileController extends BaseController
{
    public function show(Request $request, YotiClient $client)
    {
        $activityDetails = $client->getActivityDetails($request->query('token'));
        $profile = $activityDetails->getProfile();

        return view('profile', [
            'fullName' => $profile->getFullName(),
            'selfie' => $profile->getSelfie(),
            'profileAttributes' => $this->createAttributesDisplayList($profile),
        ]);
    }

    /**
     * Create attributes display list.
     *
     * @param UserProfile $profile
     *
     * @return array
     */
    private function createAttributesDisplayList(UserProfile $profile): array
    {
        $profileAttributes = [];
        foreach ($profile->getAttributesList() as $attribute) {
            switch ($attribute->getName()) {
                case UserProfile::ATTR_SELFIE:
                case UserProfile::ATTR_FULL_NAME:
                    // Selfie and full name are handled separately.
                    break;
                case UserProfile::ATTR_GIVEN_NAMES:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Given names', 'yoti-icon-profile');
                    break;
                case UserProfile::ATTR_FAMILY_NAME:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Family names', 'yoti-icon-profile');
                    break;
                case UserProfile::ATTR_DATE_OF_BIRTH:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Date of Birth', 'yoti-icon-calendar');
                    break;
                case UserProfile::ATTR_GENDER:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Gender', 'yoti-icon-gender');
                    break;
                case UserProfile::ATTR_STRUCTURED_POSTAL_ADDRESS:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Structured Postal Address', 'yoti-icon-address');
                    break;
                case UserProfile::ATTR_POSTAL_ADDRESS:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Address', 'yoti-icon-address');
                    break;
                case UserProfile::ATTR_PHONE_NUMBER:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Mobile number', 'yoti-icon-phone');
                    break;
                case UserProfile::ATTR_NATIONALITY:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Nationality', 'yoti-icon-nationality');
                    break;
                case UserProfile::ATTR_EMAIL_ADDRESS:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Email address', 'yoti-icon-email');
                    break;
                case UserProfile::ATTR_DOCUMENT_DETAILS:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Document Details', 'yoti-icon-profile');
                    break;
                case UserProfile::ATTR_DOCUMENT_IMAGES:
                    $profileAttributes[] = $this->createAttributeDisplayItem($attribute, 'Document Images', 'yoti-icon-profile');
                    break;
                default:
                    // Skip age verifications (name containing ":").
                    if (strpos($attribute->getName(), ':') === false) {
                        $profileAttributes[] = $this->createAttributeDisplayItem(
                            $attribute,
                            ucwords(str_replace('_', ' ', $attribute->getName())),
                            'yoti-icon-profile'
                        );
                    }
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

        return $profileAttributes;
    }

    /**
     * Create attribute display item.
     *
     * @param Attribute $attribute
     * @param string $displayName
     * @param string $iconClass
     *
     * @return array
     */
    private function createAttributeDisplayItem(Attribute $attribute, string $displayName, string $iconClass): array
    {
        return [
            'name' => $displayName,
            'obj' => $attribute,
            'icon' => $iconClass,
        ];
    }
}
