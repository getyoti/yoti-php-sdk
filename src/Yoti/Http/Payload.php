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
     * Get byte array.
     *
     * @return mixed
     */
    public function getByteArray()
    {
        $data = is_array($this->data) ? json_encode($this->data) : $this->data;
        // Convert string into byte array
        $byteArray = reset(unpack('C*', $data));

        return $byteArray;
    }

    /**
     * Convert string into utf8.
     *
     * @param $data
     *
     * @return array|bool|string
     */
    public function convertToUTF8($data)
    {
        return is_array($data) ? array_map('utf8_encode', $data) : utf8_encode($data);
    }
}