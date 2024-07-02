<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\YotiClient;

class AdvancedIdentityController extends BaseController
{
    public function show(YotiClient $client)
    {
        $advancedIdentityProfileJson =
            (object)[
                "profiles" => [(object)[

                    "trust_framework" => "YOTI_GLOBAL",
                    "schemes" => [(object)[

                        "label" => "identity-AL-L1",
                        "type" => "IDENTITY",
                        "objective" => "AL_L1"
                    ],
                        [
                            "label" => "identity-AL-M1",
                            "type" => "IDENTITY",
                            "objective" => "AL_M1"
                        ]
                    ]
                ]
                ]
            ]
        ;

        $policy = (new DynamicPolicyBuilder())
            ->withAdvancedIdentityProfileRequirements($advancedIdentityProfileJson)
            ->build();

        $dynamicScenario = (new DynamicScenarioBuilder())
            ->withCallbackEndpoint("/profile")
            ->withPolicy($policy)
            ->withSubject((object)[
                'subject_id' => "some_subject_id_string"
            ])
            ->build();

        return view('advanced', [
            'title' => 'Advanced Identity Check Example',
            'buttonConfig' => [
                'elements' => [
                    [
                        'domId' => 'yoti-share-button',
                        'clientSdkId' => config('yoti')['client.sdk.id'],
                        'shareUrl' => $client->createShareUrl($dynamicScenario)->getShareUrl(),
                        'button' => [
                            'label' => 'Use Yoti',
                            'align' =>  'center',
                            'width' =>  'auto',
                            'verticalAlign' => 'top'
                        ],
                        'type' => 'modal'
                    ]
                ]
            ]
        ]);
    }
}
