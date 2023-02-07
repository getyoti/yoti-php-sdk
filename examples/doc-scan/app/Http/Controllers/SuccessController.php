<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yoti\IDV\IDVClient;

class SuccessController extends BaseController
{
    public function show(Request $request, IDVClient $client)
    {
        return view('success', [
            'sessionResult' => $client->getSession($request->session()->get('YOTI_SESSION_ID')),
        ]);
    }
}
