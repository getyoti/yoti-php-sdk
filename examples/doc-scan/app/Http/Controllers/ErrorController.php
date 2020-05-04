<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ErrorController extends BaseController
{
    public function show(Request $request)
    {
        $error = 'An unknown error occurred';

        if ($request->get('yotiErrorCode')) {
            $error = 'Error Code: ' . $request->get('yotiErrorCode');
        }

        return view('error', [
            'error' => $error,
        ]);
    }
}
