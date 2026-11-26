<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yoti\DocScan\DocScanClient;
use Yoti\DocScan\Session\Create\SessionOverviewPdfOptionsBuilder;

class SuccessController extends BaseController
{
    public function show(Request $request, DocScanClient $client)
    {
        $sessionId = $request->session()->get('YOTI_SESSION_ID');
        $sessionResult = $client->getSession($sessionId);

        // Generate a session overview PDF with default options (all flags omitted).
        $sessionOverviewPdf = null;
        try {
            $pdfOptions = (new SessionOverviewPdfOptionsBuilder())
                ->withIncludeImages(true)
                ->withIncludeExtractedText(true)
                ->build();
            $sessionOverviewPdf = $client->createSessionOverviewPdf($sessionId, $pdfOptions);
        } catch (\Throwable $e) {
            // PDF generation is best-effort; do not interrupt the success page.
        }

        return view('success', [
            'sessionResult' => $sessionResult,
            'sessionOverviewPdf' => $sessionOverviewPdf,
        ]);
    }
}
