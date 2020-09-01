<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\ExtraData;

use Psr\Log\LoggerInterface;
use Yoti\Profile\ExtraData;
use Yoti\Protobuf\Sharepubapi\ExtraData as ExtraDataProto;
use Yoti\Util\Logger;

class ExtraDataConverter
{
    /**
     * @param string|null $data
     *   Base64 encoded data.
     *
     * @return \Yoti\Profile\ExtraData
     */
    public static function convertValue(?string $data, ?LoggerInterface $logger = null): ExtraData
    {
        if ($data === null) {
            return new ExtraData([]);
        }

        $logger = $logger ?? new Logger();

        $extraDataProto = new ExtraDataProto();

        try {
            $extraDataProto->mergeFromString($data);
        } catch (\Exception $e) {
            $logger->warning('Failed to parse extra data', ['exception' => $e]);
            return new ExtraData([]);
        }

        $dataEntryList = [];
        foreach ($extraDataProto->getList() as $dataEntryProto) {
            try {
                $dataEntryList[] = DataEntryConverter::convertValue(
                    $dataEntryProto->getType(),
                    $dataEntryProto->getValue(),
                    $logger
                );
            } catch (\Exception $e) {
                $logger->warning('Failed to convert data entry', ['exception' => $e]);
            }
        }

        return new ExtraData($dataEntryList);
    }
}
