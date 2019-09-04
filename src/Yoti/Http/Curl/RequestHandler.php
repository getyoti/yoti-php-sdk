<?php

namespace Yoti\Http\Curl;

use Yoti\Http\RequestHandlerInterface;
use Yoti\Http\Request;
use Yoti\Http\Response;

/**
 * Handle HTTP requests.
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * Execute HTTP request.
     *
     * @param Request $request
     *
     * @return \Yoti\Http\Response
     */
    public function execute(Request $request)
    {
        $headers = [];
        foreach ($request->getHeaders() as $name => $value) {
            $headers[] = "{$name}: {$value}";
        }

        $ch = curl_init($request->getUrl());
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());

        // Only send payload data for methods that need it.
        if ($request->getPayload()) {
            // Send payload data as a JSON string
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getPayload()->getPayloadJSON());
        }

        // Set response data
        $response = curl_exec($ch);

        // Set response code
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check if any related Curl error occurred.
        if (curl_error($ch)) {
            throw new RequestException(curl_error($ch));
        }

        // Close the session
        curl_close($ch);

        return new Response($response, $statusCode);
    }
}
