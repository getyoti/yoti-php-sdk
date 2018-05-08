<?php

namespace YotiTest\Util\Profile;

use phpseclib\File\ASN1;
use phpseclib\File\X509;
use YotiTest\TestCase;
use Yoti\Util\Profile\AnchorProcessor;

class AnchorProcessorTest extends TestCase
{
    public $ASN1;
    public $X509;
    public $anchors;
    public $anchorTypes;
    public $sourceAnchorData;
    public $verifierAnchorData;

    public function setup()
    {
        $this->ASN1 = new ASN1();
        $this->X509 = new X509();
        $this->anchorTypes = AnchorProcessor::getAnchorTypes();
        $this->sourceAnchorData = json_decode(file_get_contents(SOURCE_ANCHOR_DATA), TRUE);
        $this->verifierAnchorData = json_decode(file_get_contents(VERIFIER_ANCHOR_DATA), TRUE);
    }

    public function testSourceAnchor()
    {
        $value = [];
        $anchorFound = '';
        AnchorProcessor::anchorFound($this->sourceAnchorData, $this->anchorTypes['sources'], $value);
        if(!empty($value) && preg_match('/[a-zA-Z]+/', $value[0], $match)) {
            $anchorFound = $match[0];
        }

        $this->assertEquals('PASSPORT', $anchorFound);
    }

    public function testVerifierAnchor()
    {
        $value = [];
        $anchorFound = '';
        AnchorProcessor::anchorFound($this->verifierAnchorData, $this->anchorTypes['verifiers'], $value);
        if(!empty($value)) {
            $anchorFound = $value[0];
        }

        $this->assertEquals('YOTI_ADMIN', $anchorFound);
    }
}