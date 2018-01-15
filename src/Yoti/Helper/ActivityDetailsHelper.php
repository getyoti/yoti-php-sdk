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
    public static function getBase64Selfie(ActivityDetails $activityDetails, $imageFormat = 'jpeg')
    {
        $base64Selfie = base64_encode($activityDetails->getSelfie());

        // Make sure the image data is not empty and the format is allowed.
        if(!empty($base64Selfie) && self::isAllowedFormat($imageFormat)) {
            $imageFormat = strtolower($imageFormat);
            return "data:image/{$imageFormat};base64,{$base64Selfie}";
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
        // Make the format lower case
        $imageFormat = strtolower($imageFormat);

        // Convert the allowed formats into an array
        $allowedImageFormat = explode(',',self::ALLOWED_IMAGE_FORMAT);

        return !empty($imageFormat) && isset($allowedImageFormat[$imageFormat]);
    }

}