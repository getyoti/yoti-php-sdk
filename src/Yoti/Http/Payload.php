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
     * Get Base64 encoded of payload json string.
     *
     * @return string
     */
    public function getBase64Payload()
    {
        return base64_encode($this->getPayloadJSON());
    }

    /**
     * Get payload as a JSON string.
     *
     * @return string
     */
    public function getPayloadJSON()
    {
        $data = is_string($this->data) ? mb_convert_encoding($this->data, 'UTF-8') : $this->data;
        return json_encode($data);
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