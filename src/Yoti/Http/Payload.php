<?php
namespace Yoti\Http;

class Payload
{
    /**
     * @var mixed
     */
    private $data;

    public function __construct($data = '')
    {
        $this->data = $data;
    }

    /**
     * Get base64 encoded of payload byte array.
     *
     * @return string
     */
    public function getBase64Payload()
    {
        return base64_encode($this->convertData($this->data));
    }

    /**
     * Convert data into a binary string.
     *
     * @param mixed $data
     *
     * @return string
     */
    public function convertData($data)
    {
        if(is_array($data)) {
            // If the payload data is an array convert it into a JSON string
            $data = json_encode($data);
        }
        else if(is_string($data)) {
            // If payload data is a string convert it into utf-8.
            $data = mb_convert_encoding($data, 'UTF-8');
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getRawData());
    }
}