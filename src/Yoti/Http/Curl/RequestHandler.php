<?php

namespace Yoti\Http\Curl;

use Yoti\Http\RequestHandlerInterface;
use Yoti\Http\Request;
use Yoti\Http\Response;
use Yoti\Exception\RequestException;

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
        if ($request->getHeaders()) {
            foreach ($request->getHeaders() as $name => $value) {
                $headers[] = "{$name}: {$value}";
            }
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
        if ($response === false) {
            $error = curl_error($ch);
        }

        // Close the session
        curl_close($ch);

        // Throw if there was an error.
        if (!empty($error)) {
            throw new RequestException($error);
        }

        return new Response($response, $statusCode);
    }
}
