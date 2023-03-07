<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Yoti\Identity\Policy\PolicyBuilder;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\YotiClient;

class IdentityController extends BaseController
{
    public function show(YotiClient $client)
    {
        $policy = (new PolicyBuilder())->build();

        $redirectUri = 'https://host/redirect/';

        $shareSessionRequest = (new ShareSessionRequestBuilder())
            ->withPolicy($policy)
            ->withRedirectUri($redirectUri)
            ->build();

        $session = $client->createShareSession($shareSessionRequest);

        $qrCode = $client->createShareQrCode($session->getId());

        return view('identity', [
            'title' => 'Digital Identity Complete Example',
            'sessionId' => $session->getId(),
            'sessionStatus' => $session->getStatus(),
            'sessionExpiry' => $session->getExpiry(),
            'qrCodeId' => $qrCode->getId(),
            'qrCodeUri' => $qrCode->getUri(),
        ]);
    }
}
