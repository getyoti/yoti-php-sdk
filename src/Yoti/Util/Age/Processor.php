<?php

namespace Yoti\Util\Age;

class Processor
{
    private $profileData;

    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }

    /**
     * @return Condition
     */
    public function getCondition()
    {
        $ageData = $this->getAgeData();
        return new Condition($ageData['result'], $ageData['verifiedAge']);
    }

    protected function getAgeData()
    {
        $found = FALSE;
        $ageData = ['result'=>'', 'verifiedAge'=>''];

        $processors = $this->getProcessors();

        while(!empty($processors) && !$found)
        {
            $processor = array_shift($processors);

            if(class_exists($processor))
            {
                $processorObj = new $processor($this->profileData);
                if($processorObj instanceof AbstractAgeProcessor)
                {
                    $data = $processorObj->process();
                    if($data)
                    {
                        $ageData = $data;
                        $found = TRUE;
                    }
                }
            }
        }

        return $ageData;
    }

    public function getProcessors()
    {
        return [
            'Yoti\Util\Age\AgeUnderOverProcessor::class'
        ];
    }
}