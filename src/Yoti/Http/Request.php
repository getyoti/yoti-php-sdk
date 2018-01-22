<?php
namespace Yoti\Http;

class Request
{
    private $headers;
    private $url;

    public function __construct(array $headers, $url)
    {
        $this->setHeaders($headers);
        $this->setUrl($url);
    }

    public function setHeaders(array $headers)
    {
        if(empty($headers)) {
            throw new \Exception('Request headers cannot be empty');
        }

        $this->headers = $headers;
    }

    public function setUrl($url)
    {
        if(empty($url)) {
            throw new \Exception('Request Url cannot be empty');
        }

        $this->url = $url;
    }

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
}