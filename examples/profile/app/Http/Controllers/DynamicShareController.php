<?php

namespace App\Http\Controllers;

use Yoti\YotiClient;
use Illuminate\Routing\Controller as BaseController;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;

class DynamicShareController extends BaseController
{
    public function show(YotiClient $client)
    {
        $locationConstraint = (new LocationConstraintExtensionBuilder())
            ->withLatitude(50.8169)
            ->withLongitude(-0.1367)
            ->withRadius(100000)
            ->build();

        $policy = (new DynamicPolicyBuilder())
            ->withFullName()
            ->withDocumentDetails()
            ->withDocumentImages()
            ->withAgeOver(18)
            ->withSelfie()
            ->build();

        $scenario = (new DynamicScenarioBuilder())
            ->withPolicy($policy)
            ->withCallbackEndpoint('/profile')
            ->withExtension($locationConstraint)
            ->build();

        return view('share', [
            'title' => 'Dynamic Share Example',
            'buttonConfig' => [
                'elements' => [
                    [
                        'domId' => 'yoti-share-button',
                        'clientSdkId' => config('yoti')['client.sdk.id'],
                        'shareUrl' => $client->createShareUrl($scenario)->getShareUrl(),
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
