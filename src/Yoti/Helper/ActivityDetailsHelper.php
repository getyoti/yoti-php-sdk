<?php
namespace Yoti\Helper;

use Yoti\ActivityDetails;
use Yoti\Entity\Selfie;
use Yoti\Util\Age\Processor as AgeProcessor;

/**
 * Provide helpers for ActivityDetails.
 *
 * Class ActivityDetailsHelper
 * @package Yoti\Helper
 */
class ActivityDetailsHelper
{
    /**
     * @var \Yoti\ActivityDetails
     */
    public $activityDetails;

    public function __construct(ActivityDetails $activityDetails)
    {
        $this->activityDetails = $activityDetails;
    }

    /**
     * Get image data in base64.
     *
     * @param ActivityDetails $activityDetails
     *   Yoti user profile object.
     *
     * @return null|string
     *   Image formatted data.
     */
    public static function getBase64Selfie(ActivityDetails $activityDetails)
    {
        $base64Selfie = base64_encode($activityDetails->getSelfie());

        $selfieObj = $activityDetails->getSelfieEntity();
        $imageFormat = ($selfieObj instanceof Selfie) ? $selfieObj->getType() : 'jpeg';

        // Make sure the image data is not empty.
        if(!empty($base64Selfie)) {
            return "data:image/{$imageFormat};base64,{$base64Selfie}";
        }

        return NULL;
    }

    /**
     * @return \Yoti\Util\Age\Condition
     */
    public function getAgeCondition()
    {
        $ageProcessor = new AgeProcessor($this->activityDetails->getProfileAttribute());
        return $ageProcessor->getCondition();
    }
}