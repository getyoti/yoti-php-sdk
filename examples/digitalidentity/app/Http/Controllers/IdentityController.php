<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Yoti\DigitalIdentityClient;
use Yoti\Identity\Policy\PolicyBuilder;
use Yoti\Identity\ShareSessionRequestBuilder;

class IdentityController extends BaseController
{
    public function generateSession(DigitalIdentityClient $client)
    {
        try {

            $policy = (new PolicyBuilder())
                ->withFamilyName()
                ->withGivenNames()
                ->withFullName()
                ->withDateOfBirth()
                ->withGender()
                ->withNationality()
                ->withPhoneNumber()
                ->withSelfie()
                ->withEmail()
                ->withDocumentDetails()
                ->withDocumentImages()
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
            return view('identity', [
                'title' => 'Digital Identity Complete Example',
                'sdkId' => $client->id
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getTraceAsString());
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
