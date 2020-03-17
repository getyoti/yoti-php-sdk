<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yoti\DocScan\DocScanClient;

class MediaController extends BaseController
{
    public function show(string $id, Request $request, DocScanClient $client)
    {
        $media = $client->getMediaContent($request->session()->get('YOTI_SESSION_ID'), $id);

        $content = $media->getContent();
        $contentType = $media->getMimeType();

        if ($request->get('base64') === '1' && $contentType === 'application/octet-stream') {
            $content = base64_decode($content, true);
            $contentType = (new \finfo(FILEINFO_MIME))->buffer($content);
        }

        return response($content, 200)->header('Content-Type', $contentType);
    }
}
