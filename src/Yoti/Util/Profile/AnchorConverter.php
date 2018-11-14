<?php

namespace Yoti\Util\Profile;

use Traversable;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use Attrpubapi_v1\Anchor;
use Yoti\Entity\Anchor as YotiAnchor;

class AnchorConverter
{
    public function __construct()
    {
        $this->ASN1 = new ASN1();
        $this->X509 = new X509();
    }

    /**
     * Convert Protobuf Anchors to a map of Yoti Anchors
     * And merge the result into the reference array
     *
     * @param Anchor $anchor
     * @param $yotiAnchorsMap
     */
    public function convertToYotiAnchors(Anchor $anchor, &$yotiAnchorsMap)
    {
        $anchorSubType = $anchor->getSubType();
        $certificateList = $anchor->getOriginServerCerts();
        $yotiSignedTimeStamp = $this->createYotiSignedTimestamp($anchor);
        $X509CertsList = $this->convertCertsListToX509($anchor->getOriginServerCerts());

        foreach ($certificateList as $certificate) {
            $certObj = $this->convertCertToX509($certificate);
            $certExtArr = $certObj->tbsCertificate->extensions;

            if (count($certExtArr) > 1) {
                $extId = $certExtArr[1]->extnId;
                $extEncodedValue = $certExtArr[1]->extnValue;

                if ($decodedAnchorValue = $this->decodeAnchorValue($extEncodedValue)) {
                    $yotiAnchorsMap[$extId][] = new YotiAnchor(
                        $decodedAnchorValue,
                        $anchorSubType,
                        $yotiSignedTimeStamp,
                        $X509CertsList
                    );
                }
            }
        }
    }

    /**
     * @param string $extEncodedValue
     *
     * @return null
     */
    private function decodeAnchorValue($extEncodedValue)
    {
        $encodedBER = $this->X509->_extractBER($extEncodedValue);
        $decodedValArr = $this->ASN1->decodeBER($encodedBER);
        if (isset($decodedValArr[0]['content'][0]['content'])) {
            return $decodedValArr[0]['content'][0]['content'];
        }
        return NULL;
    }

    /**
     * @param \Attrpubapi_v1\Anchor $anchor
     *
     * @return \Yoti\Entity\SignedTimeStamp
     */
    private function createYotiSignedTimestamp(Anchor $anchor)
    {
        $signedTimeStamp = new \Compubapi_v1\SignedTimestamp();
        $signedTimeStamp->mergeFromString($anchor->getSignedTimeStamp());

        $timeInSeconds = round($signedTimeStamp->getTimestamp()/1000000);
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($timeInSeconds);

        $yotiSignedTimeStamp = new \Yoti\Entity\SignedTimeStamp(
            $signedTimeStamp->getVersion(),
            $dateTime
        );

        return $yotiSignedTimeStamp;
    }

    /**
     * @param Traversable $certificateList
     *
     * @return array
     */
    private function convertCertsListToX509(Traversable $certificateList) {
        $certsList = [];
        foreach($certificateList as $certificate) {
            if ($X509CertObj = $this->convertCertToX509($certificate)) {
                $certsList[] = $X509CertObj;
            }
        }
        return $certsList;
    }

    /**
     * Return X509 Cert Object.
     *
     * @param string $certificate
     *
     * @return mixed
     */
    private function convertCertToX509($certificate) {
        $X509Data = $this->X509->loadX509($certificate);
        return json_decode(json_encode($X509Data), FALSE);
    }
}