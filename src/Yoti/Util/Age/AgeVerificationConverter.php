<?php

namespace Yoti\Util\Age;

class AgeVerificationConverter
{
    /**
     * @var array
     * e.g [
     *      'given_name' => new Attribute('given_name','Test Given name', [], []),
     *      'age_over:20' => new Attribute('age_over:20' => 'true', [], []),
     *      ...
     * ]
     */
    private $profileAttributesMap;

    public function __construct(array $profileAttributesMap)
    {
        $this->profileAttributesMap = $profileAttributesMap;
    }

    /**
     * Return list of age verification.
     *
     * @return array of AgeVerification
     * e.g [
     *      'age_under:18' => new AgeVerification(...),
     *      'age_over:50' => new AgeVerification(...),
     *      ...
     * ]
     */
    public function getAgeVerificationsFromAttrsMap()
    {
        $ageVerificationsArr = [];

        foreach($this->profileAttributesMap as $attrName => $attributeObj)
        {
            foreach($this->getAgeProcessors() as $ageProcessorClass)
            {
                $abstractAgeProcessorClass = '\\Yoti\\Util\\Age\\AbstractAgeProcessor';
                if (
                    NULL !== $attrName
                    && NULL !== $attributeObj
                    && is_subclass_of($ageProcessorClass, $abstractAgeProcessorClass)
                )
                {
                    /**
                     * @var \Yoti\Util\Age\AbstractAgeProcessor $ageProcessorObj
                     */
                    $ageProcessorObj = new $ageProcessorClass($attributeObj);

                    if ($ageVerification = $ageProcessorObj->process())
                    {
                        $ageVerificationsArr[$attrName] = $ageVerification;
                    }
                }
            }
        }

        return $ageVerificationsArr;
    }

    /**
     * Age processor config
     * Add more processor to this array if you want them applied as age filter
     *
     * @return array
     */
    private function getAgeProcessors()
    {
        return [
            '\\Yoti\\Util\\Age\\AgeUnderVerificationProcessor',
            '\\Yoti\\Util\\Age\\AgeOverVerificationProcessor',
        ];
    }
}