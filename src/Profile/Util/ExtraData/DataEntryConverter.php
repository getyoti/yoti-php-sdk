<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\ExtraData;

use Psr\Log\LoggerInterface;
use Yoti\Exception\ExtraDataException;
use Yoti\Protobuf\Sharepubapi\DataEntry\Type as DataEntryTypeProto;
use Yoti\Util\Logger;

class DataEntryConverter
{
    /**
     * @var ThirdPartyAttributeConverter
     */
    private $thirdPartyAttributeConverter;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->thirdPartyAttributeConverter = new ThirdPartyAttributeConverter($logger);
    }

    /**
     * @param int $type
     * @param string $value
     *
     * @return mixed
     *
     * @throws \Yoti\Exception\ExtraDataException
     */
    public function convert(int $type, string $value)
    {
        if (strlen($value) === 0) {
            throw new ExtraDataException('Value is empty');
        }

        switch ($type) {
            case DataEntryTypeProto::THIRD_PARTY_ATTRIBUTE:
                return $this->thirdPartyAttributeConverter->convert($value);
            default:
                throw new ExtraDataException("Unsupported data entry '{$type}'");
        }
    }

    /**
     * @deprecated replaced by DataEntryConverter::convert()
     *
     * @param int $type
     * @param string $value
     *
     * @return mixed
     *
     * @throws \Yoti\Exception\ExtraDataException
     */
    public static function convertValue(int $type, string $value)
    {
        return (new self(new Logger()))->convert($type, $value);
    }
}
