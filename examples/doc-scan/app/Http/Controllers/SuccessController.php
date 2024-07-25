<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yoti\DocScan\DocScanClient;

class SuccessController extends BaseController
{
    public function show(Request $request, DocScanClient $client)
    {
        return view('success', [
            'sessionResult' => $client->getSession('894a51f1-f8fc-4986-85e7-fb8a56d61388'),
        ]);
    }
}
