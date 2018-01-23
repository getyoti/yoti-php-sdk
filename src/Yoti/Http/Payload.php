<?php
namespace Yoti\Http;

class Payload
{
    private $data;

    public function __construct($data)
    {
        $this->data = $this->convertToUTF8($data);
    }

    /**
     * Get byte array of a string or an array.
     *
     * @return mixed
     */
    public function getByteArray()
    {
        // If the payload data is an array convert it into a binary string with serialize
        $data = is_array($this->data) ? serialize($this->data) : $this->data;
        // Convert string into byte array
        $byteArray = reset(unpack('C*', $data));

        return $byteArray;
    }

    /**
     * If $data is a string convert it into utf-8.
     *
     * @param $data
     *
     * @return array|string
     */
    public function convertToUTF8($data)
    {
        return is_array($data) ? $data : mb_convert_encoding($data, 'UTF-8');
    }
}