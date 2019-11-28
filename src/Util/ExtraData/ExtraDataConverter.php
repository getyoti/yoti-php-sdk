<?php

namespace Yoti\Util\ExtraData;

use Yoti\Entity\ExtraData;
use Yoti\Protobuf\Sharepubapi\ExtraData as ExtraDataProto;

class ExtraDataConverter
{
    /**
     * @param string $data
     *   Base64 encoded data.
     *
     * @return \Yoti\Entity\ExtraData
     */
    public static function convertValue($data)
    {
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
