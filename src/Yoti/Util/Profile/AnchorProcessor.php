<?php
namespace Yoti\Util\Profile;

use Traversable;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use Yoti\Entity\Anchor as YotiAnchor;

class AnchorProcessor
{
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
        $anchorTypes = self::getAnchorTypes();
        $addedAnchors = [];

        foreach ($anchorList as $anchor) {
            $certificateList = $anchor->getOriginServerCertsList();
            foreach ($certificateList as $certificate) {
                $contents = $certificate->getContents();
                // Decode the content with ASN1
                $BER = $this->X509->_extractBER($contents);
                $this->ASN1->loadOIDs($this->X509->oids);
                $certificateContent = $this->ASN1->decodeBER($BER);

                foreach ($anchorTypes as $type => $oid) {
                    $searchResult = [];
                    if (self::anchorFound($certificateContent, $oid, $searchResult)) {
                        // The value could be encoded - so decode it with ASN1
                        $anchorValue = $searchResult[0];
                        $decodedValue = $this->ASN1->decodeBER($anchorValue);
                        if (is_array($decodedValue)) {
                            $keyExists = isset($decodedValue[0]['content'][0]['content']);
                            $anchorValue = $keyExists ? $decodedValue[0]['content'][0]['content'] : '';
                        }

                        // Make the anchors values unique
                        if (!in_array($anchorValue, $addedAnchors, TRUE)) {
                            $X509CertsList = $this->convertCertsListToX509($anchor->getOriginServerCertsList());
                            $yotiAnchor = new YotiAnchor(
                                $anchorValue,
                                $anchor->getArtifactLink(),
                                $anchor->getSubType(),
                                $anchor->getSignature(),
                                $anchor->getSignedTimeStamp(),
                                $X509CertsList,
                                ''
                            );
                            $anchorsData[$type][] = $yotiAnchor;
                            $addedAnchors[] = $anchorValue;
                        }
                    }
                }
            }
        }

        return $anchorsData;
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

    public function convertCertToX509(\Protobuf\Stream $certificate) {
        $contents = $certificate->getContents();
        $X509Data = $this->X509->loadX509($contents);
        $X509Obj = json_decode(json_encode($X509Data), FALSE);

        return $X509Obj ? $X509Obj->tbsCertificate : NULL;
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