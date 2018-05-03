<?php
namespace Yoti\Util\Profile;

use phpseclib\File\ASN1;
use phpseclib\File\X509;

use Protobuf\MessageCollection;

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
     * @param MessageCollection $anchorList
     *
     * @return array
     */
    public function process(MessageCollection $anchorList)
    {
        $anchorsData = ['sources'=>[], 'verifiers'=>[]];
        $anchorTypes = self::getAnchorTypes();

        foreach ($anchorList as $anchor) {
            $certificateList = $anchor->getOriginServerCertsList();

            foreach ($certificateList as $certificate) {
                $contents = $certificate->getContents();
                $BER = $this->X509->_extractBER($contents);
                $this->ASN1->loadOIDs($this->X509->oids);
                $certificateContent = $this->ASN1->decodeBER($BER);
                foreach ($anchorTypes as $type => $oid) {
                    $res = [];
                    self::extractAnchorsData($certificateContent, $oid, $res);
                    foreach ($res as $anchorValue) {
                        $value = $anchorValue;
                        $decodedValue = $this->ASN1->decodeBER($anchorValue);
                        if (is_array($decodedValue)) {
                            $value = $decodedValue[0]['content'][0]['content'];
                        }
                        $anchorsData[$type][] = $value;
                    }
                }
            }
        }

        return $anchorsData;
    }

    /**
     * Extract anchors data from the certificate.
     *
     * @param array $certData
     * @param $oid
     * @param $res
     */
    protected static function extractAnchorsData(array $certData, $oid, &$res)
    {
        self::recursiveSearch($certData, $oid, $res);
    }

    /**
     * Perform recursive search for anchors on certificate data.
     *
     * @param array $data
     * @param $oid
     * @param $res
     */
    protected static function recursiveSearch(array $data, $oid, &$res)
    {
        foreach ($data as $key => $row) {
            if (isset($row['content'])) {
                $content = $row['content'];
                if (is_array($content)) {
                    self::recursiveSearch($content, $oid, $res);
                }
                elseif (!is_object($content) && is_string($content) && strcmp($oid, $content) === 0) {
                    if (isset($data[$key+1]['content'])) {
                        $res[] = $data[$key+1]['content'];
                    }
                    break;
                }
            }
        }
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