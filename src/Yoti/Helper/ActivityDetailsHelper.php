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

    /**
     * Create selfie image file.
     *
     * @param ActivityDetails $activityDetails
     *   Yoti user object.
     * @param string $fileName
     *   Image file name.
     * @param null|string $selfieDir
     *   Image file directory.
     *
     * @return bool|string
     *   File full path or false.
     */
    public static function createSelfieImage(ActivityDetails $activityDetails, $fileName = 'selfie.jpeg', $selfieDir = NULL)
    {
        // Get the image format.
        $imageFormat = !empty($fileName) ? pathinfo($fileName, PATHINFO_EXTENSION) : '';

        // If the image format is not allowed return false.
        if(self::isAllowedFormat($imageFormat)) {
            return FALSE;
        }

        // If no directory is provided save it into the temp dir.
        if($selfieDir === '.' || !is_dir($selfieDir)) {
            $selfieDir = sys_get_temp_dir();
        }

        // Construct image full path.
        $selfieFullPath = $selfieDir . "/{$fileName}";

        // Create the image in the directory.
        $selfieFile = file_put_contents($selfieFullPath, $activityDetails->getSelfie(), LOCK_EX);

        // Return the path if successful or false.
        return $selfieFile ? $selfieFullPath : FALSE;
    }

}