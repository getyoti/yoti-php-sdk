<?php
namespace Yoti\Http;

use Yoti\Exception\AmlException;

class AmlResult
{
    const ON_PEP_LIST_KEY = 'on_pep_list';
    const ON_FRAUD_LIST_KEY = 'on_fraud_list';
    const ON_WATCH_LIST_KEY = 'on_watch_list';

    /**
     * Politically exposed person.
     *
     * @var bool
     */
    private $onPepList;

    /**
     * Fraud list.
     *
     * @var bool
     */
    private $onFraudList;

    /**
     * Watch list.
     *
     * @var bool
     */
    private $onWatchList;

    /**
     * Raw result.
     *
     * @var array
     */
    private $rawResult;

    /**
     * AmlResult constructor.
     *
     * @param array $result
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function __construct(array $result)
    {
        $this->rawResult = $result;
        $this->setAttributes();
    }

    /**
     * Check if user is a politically exposed person.
     *
     * @return bool
     */
    public function isOnPepList()
    {
        return $this->onPepList;
    }

    /**
     * Check if user is on a fraud list.
     *
     * @return bool
     */
    public function isOnFraudList()
    {
        return $this->onFraudList;
    }

    /**
     * Check if user is on a watch list.
     *
     * @return bool
     */
    public function isOnWatchList()
    {
        return $this->onWatchList;
    }

    /**
     * Set attribute values.
     *
     * @throws \Yoti\Exception\AmlException
     */
    private function setAttributes()
    {
        $result = $this->rawResult;
        // Check if no attribute is missing from the result
        AmlResult::checkAttributes($result);

        $this->onPepList = (bool) $result[self::ON_PEP_LIST_KEY];
        $this->onFraudList = (bool) $result[self::ON_FRAUD_LIST_KEY];
        $this->onWatchList = (bool) $result[self::ON_WATCH_LIST_KEY];
    }

    /**
     * Check if all the attributes are included in the result.
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
            throw new AmlException('Missing attributes from the result: ' . implode(',', $missingAttr) , 106);
        }
    }

    /**
     * Returns json string of the raw data.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->rawResult);
    }
}