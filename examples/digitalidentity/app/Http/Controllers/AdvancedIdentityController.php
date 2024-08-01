<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Yoti\DigitalIdentityClient;
use Yoti\Identity\Policy\PolicyBuilder;
use Yoti\Identity\ShareSessionRequestBuilder;

class AdvancedIdentityController extends BaseController
{
    public function generateSession(DigitalIdentityClient $client)
    {
        try {
            $advancedIdentityProfileJson =
                (object)[
                "profiles" => [(object)[

                        "trust_framework" => "YOTI_GLOBAL",
                        "schemes" => [(object)[

                                "label" => "identity-AL-L1",
                                "type" => "IDENTITY",
                                "objective"=> "AL_L1"
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

            $policy = (new PolicyBuilder())
                ->withAdvancedIdentityProfileRequirements((object)$advancedIdentityProfileJson)
                ->build();

            $redirectUri = 'https://host/redirect/';

            $shareSessionRequest = (new ShareSessionRequestBuilder())
                ->withPolicy($policy)
                ->withRedirectUri($redirectUri)
                ->build();
            $session = $client->createShareSession($shareSessionRequest);
            return $session->getId();
        }
        catch (\Throwable $e) {
            Log::error($e->getTraceAsString());
            throw new BadRequestHttpException($e->getMessage());
        }
    }
    public function show(DigitalIdentityClient $client)
    {
        try {
            return view('advancedidentity', [
                'title' => 'Digital Identity(Advanced) Complete Example',
                'sdkId' => $client->id
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getTraceAsString());
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
