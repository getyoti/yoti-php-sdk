<?php
namespace Yoti\Http;

use Yoti\Exception\AmlException;

class AmlResult
{
    const ON_PEP_LIST_KEY = 'on_pep_list';
    const ON_FRAUD_LIST_KEY = 'on_fraud_list';
    const ON_WATCH_LIST_KEY = 'on_watch_list';

    private $onPepList;

    private $onFraudList;

    private $onWatchList;

    /**
     * AmlResult constructor.
     *
     * @param array $result
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function __construct(array $result)
    {
        $this->setAttributes($result);
    }

    public function isOnPepList()
    {
        return $this->onPepList;
    }

    public function isOnFraudList()
    {
        return $this->onFraudList;
    }

    public function isOnWatchList()
    {
        return $this->onWatchList;
    }

    /**
     * @param array $result
     *
     * @throws \Yoti\Exception\AmlException
     */
    private function setAttributes(array $result)
    {
        // Check no attribute is missing from the result
        AmlResult::checkAttributes($result);

        $this->onPepList = (bool) $result[self::ON_PEP_LIST_KEY];
        $this->onFraudList = (bool) $result[self::ON_FRAUD_LIST_KEY];
        $this->onWatchList = (bool) $result[self::ON_WATCH_LIST_KEY];
    }

    /**
     * Check all the attributes are included in the result.
     *
     * @param array $result
     *
     * @throws \Yoti\Exception\AmlException
     */
    public static function checkAttributes(array $result)
    {
        $expectedAttributes = [
            self::ON_PEP_LIST_KEY,
            self::ON_WATCH_LIST_KEY,
            self::ON_WATCH_LIST_KEY,
        ];
        $providedAttributes = array_keys($result);
        $missingAttr = array_diff($expectedAttributes, $providedAttributes);

        // Throw an error if any expected attribute is missing.
        if(!empty($missingAttr))
        {
            throw new AmlException('Missing attributes from the result: ' . implode(',', $missingAttr) , 404);
        }
    }
}