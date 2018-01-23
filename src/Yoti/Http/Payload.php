<?php
namespace Yoti\Http;

class Payload
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get byte array of a string or an array.
     *
     * @return mixed
     */
    public function getByteArray()
    {
        $data = $this->convertData($this->data);
        // Convert string into byte array
        $byteArray = array_values(unpack('C*', $data));

        return $byteArray;
    }

    /**
     * Convert data into a binary string.
     *
     * @param $data
     *
     * @return mixed|string
     */
    public function convertData($data)
    {
        if(is_array($data)) {
            // If the payload data is an array convert it into a binary string
            $data = serialize($data);
        }
        else if(is_string($data)) {
            // If payload data is a string convert it into utf-8.
            $data = mb_convert_encoding($data, 'UTF-8');
        }

        return $data;
    }
}