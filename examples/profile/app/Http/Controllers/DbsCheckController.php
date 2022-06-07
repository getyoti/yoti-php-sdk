<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\YotiClient;

class DbsCheckController extends BaseController
{
    public function show(YotiClient $client)
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withIdentityProfileRequirements((object)[
                'trust_framework' => 'UK_TFIDA',
                'scheme' => [
                    'type' => 'DBS',
                    'objective' => 'BASIC'
                ]
            ])
            ->build();

        $dynamicScenario = (new DynamicScenarioBuilder())
            ->withCallbackEndpoint("/profile")
            ->withPolicy($dynamicPolicy)
            ->withSubject((object)[
                'subject_id' => "some_subject_id_string"
            ])
            ->build();

        return view('dbs', [
            'title' => 'DBS Check Example',
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