<?php
namespace Yoti\Helper;

use Yoti\ActivityDetails;

/**
 * Provide helpers for ActivityDetails.
 *
 * Class ActivityDetailsHelper
 * @package Yoti\Helper
 */
class ActivityDetailsHelper
{
    const ALLOWED_IMAGE_FORMAT = 'jpeg,jpg,png';

    /**
     * Get image data in base64.
     *
     * @param ActivityDetails $activityDetails
     *   Yoti user profile object.
     * @param string $imageFormat
     *   Image format.
     *
     * @return null|string
     *   Image formatted data.
     */
    public static function getBase64Selfie(ActivityDetails $activityDetails, $imageFormat = 'jpg')
    {
        $selfieBase64Data = base64_encode($activityDetails->getSelfie());

        // Put the format in lower case
        $imageFormat = strtolower($imageFormat);
        if(!empty($selfieBase64Data) && self::isAllowedFormat($imageFormat)) {
            return "data:image/{$imageFormat};base64,{$selfieBase64Data}";
        }

        return NULL;
    }

    /**
     * Check the image format.
     *
     * @param $imageFormat
     *   Image format.
     *
     * @return bool
     *   TRUE or FALSE.
     */
    public static function isAllowedFormat($imageFormat)
    {
        // Convert the provided format into an array
        $allowedImageFormat = explode(',',self::ALLOWED_IMAGE_FORMAT);

        return !empty($imageFormat) && in_array($imageFormat, $allowedImageFormat, TRUE);
    }

}