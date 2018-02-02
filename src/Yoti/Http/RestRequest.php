<?php
namespace Yoti\Http;

class RestRequest extends AbstractRequest
{
    const ARISTOTLE_API = '';

    /**
     * Make request
     *
     * @return array
     */
    public function exec()
    {
        $result = [
            'response' => '',
            'http_code'=> 0,
        ];

        $ch = curl_init($this->url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->httpMethod);

        if (
            $this->httpMethod === self::METHOD_POST
            || $this->httpMethod === self::METHOD_PUT
            || $this->httpMethod === self::METHOD_PATCH
        ) {
            // Send payload data as a JSON string
            $payloadJSON = json_encode($this->payload->getRawData());
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJSON);
        }

        // Set response
        $result['response'] = curl_exec($ch);
        // Set response code
        $result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $result;
    }
}