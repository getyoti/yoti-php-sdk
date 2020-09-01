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
     * @param int $type
     * @param string $value
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return mixed
     *
     * @throws \Yoti\Exception\ExtraDataException
     */
    public static function convertValue(int $type, string $value, ?LoggerInterface $logger = null)
    {
        $logger = $logger ?? new Logger();

        if (strlen($value) === 0) {
            throw new ExtraDataException('Value is empty');
        }

        switch ($type) {
            case DataEntryTypeProto::THIRD_PARTY_ATTRIBUTE:
                return ThirdPartyAttributeConverter::convertValue($value, $logger);
            default:
                throw new ExtraDataException("Unsupported data entry '{$type}'");
        }
    }
}
