<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Yoti\DigitalIdentityClient;
use Yoti\Identity\Policy\PolicyBuilder;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\YotiClient;

class DbsController extends BaseController
{
    public function generateSession(DigitalIdentityClient $client)
    {
        try {
            $advancedIdentityProfileJson =
                (object)[
                "profiles" => [(object)[

                        "trust_framework" => "UK_TFIDA",
                        "schemes" => [(object)[

                                "label" => "identity-AL-L1",
                                "type" => "DBS",
                                "objective"=> "BASIC"
                            ],
                            [
                                "label" => "identity-AL-M1",
                                "type" => "DBS",
                                "objective" => "BASIC"
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
            return view('dbs', [
                'title' => 'Digital Identity DBS Check Example',
                'sdkId' => $client->id
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getTraceAsString());
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
