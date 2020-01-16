<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\ExtraData;

use Yoti\Profile\ExtraData\ExtraData;
use Yoti\Protobuf\Sharepubapi\ExtraData as ExtraDataProto;

class ExtraDataConverter
{
    /**
     * @param string|null $data
     *   Base64 encoded data.
     *
     * @return \Yoti\Profile\ExtraData\ExtraData
     */
    public static function convertValue(?string $data): ExtraData
    {
        if ($data === null) {
            return new ExtraData([]);
        }

        $extraDataProto = new ExtraDataProto();

        try {
            $extraDataProto->mergeFromString($data);
        } catch (\Exception $e) {
            error_log(sprintf('Failed to parse extra data: %s', $e->getMessage()), 0);
            return new ExtraData([]);
        }

        $dataEntryList = [];
        foreach ($extraDataProto->getList() as $dataEntryProto) {
            try {
                $dataEntryList[] = DataEntryConverter::convertValue(
                    $dataEntryProto->getType(),
                    $dataEntryProto->getValue()
                );
            } catch (\Exception $e) {
                error_log(sprintf('Failed to convert data entry: %s', $e->getMessage()), 0);
            }
        }

        return new ExtraData($dataEntryList);
    }
}
