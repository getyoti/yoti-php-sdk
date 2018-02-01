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

        // Set response
        $result['response'] = curl_exec($ch);
        // Set response code
        $result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $result;
    }

    /**
     * Check the http method is allowed.
     *
     * @param $httpMethod
     *
     * @return bool
     */
    public static function isAllowed($httpMethod)
    {
        $allowedMethods = [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_DELETE,
            self::METHOD_PATCH,
        ];

        return in_array($httpMethod, $allowedMethods, TRUE);
    }
}