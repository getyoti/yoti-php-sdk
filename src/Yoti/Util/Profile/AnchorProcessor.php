<?php
namespace Yoti\Util\Profile;

use Traversable;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use Attrpubapi_v1\Anchor;
use Yoti\Entity\Anchor as YotiAnchor;

class AnchorProcessor
{
    const SOURCES_OID = '1.3.6.1.4.1.47127.1.1.1';
    const VERIFIERS_OID = '1.3.6.1.4.1.47127.1.1.2';

    /**
     * @var ASN1
     */
    protected $ASN1;
    /**
     * @var X509
     */
    protected $X509;

    /**
     * AnchorProcessor constructor.
     */
    public function __construct()
    {
        $this->ASN1 = new ASN1();
        $this->X509 = new X509();
    }

    /**
     * @param Traversable $anchorList
     *
     * @return array
     */
    public function process(Traversable $anchorList)
    {
        $anchorsData = ['sources'=>[], 'verifiers'=>[]];
        $newAnchorsData = [];

        $anchorTypes = self::getAnchorTypes();

        foreach ($anchorList as $anchor) {
            $certificateList = $anchor->getOriginServerCerts();
            // Create SignedTimeStamp object from bytes
            $yotiSignedTimeStamp = $this->createYotiSignedTimestamp($anchor);
            $anchorSubType = $anchor->getSubType();
            $X509CertsList = $this->convertCertsListToX509($anchor->getOriginServerCerts());

            foreach ($certificateList as $certificate) {
                // Decode the content with ASN1
                //$BER = $this->X509->_extractBER($certificate);

               // $this->ASN1->loadOIDs($this->X509->oids);
                //$certificateContent = $this->ASN1->decodeBER($BER);

                $certObj = $this->convertCertToX509($certificate);
                $certExt = $certObj->tbsCertificate->extensions;
                if (count($certExt) > 1) {
                    $extId = $certExt[1]->extnId;
                    $extEncodedValue = $certExt[1]->extnValue;

                    $valBER = $this->X509->_extractBER($extEncodedValue);
                    $decodedValArr = $this->ASN1->decodeBER($valBER);
                    if (isset($decodedValArr[0]['content'][0]['content'])) {
                        $anchorValue = $decodedValArr[0]['content'][0]['content'];
                        $newAnchorsData[$extId][] = new YotiAnchor(
                            $anchorValue,
                            $anchorSubType,
                            $yotiSignedTimeStamp,
                            $X509CertsList
                        );
                    }
                }
            }
        }

        if (isset($newAnchorsData[self::SOURCES_OID])) {
            $anchorsData['sources'] = $newAnchorsData[self::SOURCES_OID];
        }

        if (isset($newAnchorsData[self::VERIFIERS_OID])) {
            $anchorsData['verifiers'] = $newAnchorsData[self::VERIFIERS_OID];
        }
        return $anchorsData;
    }

    /**
     * @param Attrpubapi_v1\Anchor $anchor
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
     * Extract anchor data from the certificate.
     *
     * @param array $certData
     * @param $oid
     * @param $res
     *
     * @return bool
     */
    public static function anchorFound(array $certData, $oid, &$res)
    {
        return self::recursiveSearch($certData, $oid, $res);
    }

    /**
     * Perform recursive search for anchors on certificate data.
     *
     * @param array $data
     * @param $oid
     * @param $res
     *
     * @return bool
     */
    protected static function recursiveSearch(array $data, $oid, &$res)
    {
        foreach ($data as $key => $row) {
            if (isset($row['content'])) {
                $content = $row['content'];
                if (is_array($content) && self::recursiveSearch($content, $oid, $res)) {
                    return TRUE;
                }
                if (is_string($content) && strcmp($oid, $content) === 0) {
                    // Get the content value from the next row
                    if (isset($data[$key+1]['content'])) {
                        $res[] = $data[$key+1]['content'];
                    }
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function convertCertsListToX509(Traversable $certificateList) {
        $certsList = [];
        foreach($certificateList as $certificate) {
            $X509Cert = $this->convertCertToX509($certificate);
            if (NULL !== $X509Cert) {
                $certsList[] = $X509Cert;
            }
        }
        return $certsList;
    }

    public function convertCertToX509($certificate) {
        $X509Data = $this->X509->loadX509($certificate);
        return json_decode(json_encode($X509Data), FALSE);
    }

    /**
     * @return array
     */
    public static function getAnchorTypes()
    {
        return [
            'sources' => '1.3.6.1.4.1.47127.1.1.1',
            'verifiers' => '1.3.6.1.4.1.47127.1.1.2',
        ];
    }
}