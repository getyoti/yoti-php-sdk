<?php

namespace Yoti\Util\ExtraData;

use Yoti\Entity\ExtraData;
use Yoti\Sharepubapi\ExtraData as ExtraDataProto;

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
        $extraDataProto->mergeFromString(base64_decode($data));

        $dataEntryList = [];
        foreach ($extraDataProto->getList() as $dataEntryProto) {
            $dataEntryList[] = DataEntryConverter::convertValue(
                $dataEntryProto->getType(),
                $dataEntryProto->getValue()
            );
        }

        return new ExtraData($dataEntryList);
    }
}
