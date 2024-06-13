<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Yoti\DigitalIdentityClient;
use Yoti\Identity\Policy\PolicyBuilder;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\YotiClient;

class Identity2Controller extends BaseController
{
    public function show(DigitalIdentityClient $client)
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

            $createdQrCode = $client->createShareQrCode($session->getId());

            $fetchedQrCode = $client->fetchShareQrCode($createdQrCode->getId());

            $sessionFetched = $client->fetchShareSession($session->getId());

            return view('identity2', [
                'title' => 'Digital Identity Complete Example',
                // Creating session
                'sessionId' => $session->getId(),
                'sessionStatus' => $session->getStatus(),
                'sessionExpiry' => $session->getExpiry(),
                // Creating QR code
                'createdQrCodeId' => $createdQrCode->getId(),
                'createdQrCodeUri' => $createdQrCode->getUri(),
                // Fetch QR code
                'fetchedQrCodeExpiry' => $fetchedQrCode->getExpiry(),

                'fetchedQrCodeRedirectUri' => $fetchedQrCode->getRedirectUri(),
                'fetchedQrCodeSessionId' => $fetchedQrCode->getSession()->getId(),
                'fetchedQrCodeSessionStatus' => $fetchedQrCode->getSession()->getStatus(),
                'fetchedQrCodeSessionExpiry' => $fetchedQrCode->getSession()->getExpiry(),
                // Fetch session
                'fetchedSessionId' => $sessionFetched->getId(),
                'fetchedSessionStatus' => $sessionFetched->getStatus(),
                'fetchedSessionExpiry' => $sessionFetched->getExpiry(),
                'fetchedSessionCreated' => $sessionFetched->getCreated(),
                'fetchedSessionUpdated' => $sessionFetched->getUpdated(),
                'sdkId' => $client->id

            ]);
        } catch (\Throwable $e) {
            Log::error($e->getTraceAsString());
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
