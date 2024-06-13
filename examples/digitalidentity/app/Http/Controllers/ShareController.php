<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ShareController extends BaseController
{
    public function show()
    {
        return view('share', [
            'title' => 'We now accept Yoti',
            'buttonConfig' => [
                'elements' => [
                    [
                        'domId' => 'yoti-share-button',
                        'clientSdkId' => config('yoti')['client.sdk.id'],
                        'scenarioId' => config('yoti')['scenario.id'],
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
