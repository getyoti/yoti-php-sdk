<?php

namespace Yoti\Util\Age;

use Yoti\Entity\AgeVerification;

class Processor
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
     * return list of age verifications.
     *
     * @return array of ageVerification
     * e.g [
     *      'age_under:18' => new AgeVerification(...),
     *      'age_over:50' => new AgeVerification(...),
     *      ...
     * ]
     */
    public function findAgeVerifications()
    {
        $ageVerificationsArr = [];

        foreach($this->profileAttributesMap as $attrName => $attributeObj)
        {
            foreach($this->getAgeProcessors() as $processorClass)
            {
                $ageProcessorInterface = '\\Yoti\\Util\\Age\\AgeProcessorInterface';
                if (
                    NULL !== $attrName
                    && is_subclass_of($processorClass, $ageProcessorInterface)
                )
                {
                    /**
                     * @var AgeProcessorInterface $processorObj
                     */
                    $processorObj = new $processorClass($attributeObj);

                    if ($result = $processorObj->process())
                    {
                        $ageVerificationsArr[$attrName] = new AgeVerification(
                            $attributeObj,
                            $result['checkType'],
                            $result['age'],
                            $result['result']
                        );
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
            '\\Yoti\\Util\\Age\\AgeUnderProcessor',
            '\\Yoti\\Util\\Age\\AgeOverProcessor',
        ];
    }
}