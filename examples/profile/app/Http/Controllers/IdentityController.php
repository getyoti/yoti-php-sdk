<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Yoti\Identity\Policy\PolicyBuilder;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\YotiClient;

class IdentityController extends BaseController
{
    public function show(YotiClient $client)
    {
        try {
            $policy = (new PolicyBuilder())->build();

            $redirectUri = 'https://host/redirect/';

            $shareSessionRequest = (new ShareSessionRequestBuilder())
                ->withPolicy($policy)
                ->withRedirectUri($redirectUri)
                ->build();

            $session = $client->createShareSession($shareSessionRequest);

            $createdQrCode = $client->createShareQrCode($session->getId());

            $fetchedQrCode = $client->fetchShareQrCode($createdQrCode->getId());

            return view('identity', [
                'title' => 'Digital Identity Complete Example',
                'sessionId' => $session->getId(),
                'sessionStatus' => $session->getStatus(),
                'sessionExpiry' => $session->getExpiry(),
                'createdQrCodeId' => $createdQrCode->getId(),
                'createdQrCodeUri' => $createdQrCode->getUri(),
                'fetchedQrCodeExpiry' => $fetchedQrCode->getExpiry(),
                'fetchedQrCodeExtensions' => $fetchedQrCode->getExtensions(),
                'fetchedQrCodeRedirectUri' => $fetchedQrCode->getRedirectUri(),
                'fetchedQrCodeSessionId' => $fetchedQrCode->getSession()->getId(),
                'fetchedQrCodeSessionStatus' => $fetchedQrCode->getSession()->getStatus(),
                'fetchedQrCodeSessionExpiry' => $fetchedQrCode->getSession()->getExpiry(),
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getTraceAsString());
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
