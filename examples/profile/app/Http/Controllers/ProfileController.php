<?php

namespace App\Http\Controllers;

use Yoti\YotiClient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ProfileController extends BaseController
{
    public function show(Request $request)
    {
        // Get the token
        $profileAttributes = [];

        $yotiClient = new YotiClient(getenv('YOTI_SDK_ID'), getenv('YOTI_KEY_FILE_PATH'));

        $activityDetails = $yotiClient->getActivityDetails($request->query('token'));
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

        return view('profile', [
            'fullName' => $profile->getFullName(),
            'profile' => $profile,
            'profileAttributes' => $profileAttributes,
        ]);
    }
}
