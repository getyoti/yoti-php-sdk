<?php
namespace Yoti\Http;

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
     */
    public function __construct(array $result)
    {
        $this->setAttributes($result);
    }

    public function getOnPepList()
    {
        return $this->onPepList;
    }

    public function getOnFraudList()
    {
        return $this->onFraudList;
    }

    public function getOnWatchList()
    {
        return $this->onWatchList;
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
     */
    private function setAttributes(array $result)
    {
        $this->onPepList = isset($result[self::ON_PEP_LIST_KEY]) ? (bool) $result[self::ON_PEP_LIST_KEY] : FALSE;
        $this->onFraudList = isset($result[self::ON_FRAUD_LIST_KEY]) ? (bool) $result[self::ON_FRAUD_LIST_KEY] : FALSE;
        $this->onWatchList = isset($result[self::ON_WATCH_LIST_KEY]) ? (bool) $result[self::ON_WATCH_LIST_KEY] : FALSE;
    }

    /**
     * @return array
     */
    public function getResponseArray()
    {
        return [
            self::ON_PEP_LIST_KEY => $this->onPepList,
            self::ON_FRAUD_LIST_KEY => $this->onFraudList,
            self::ON_WATCH_LIST_KEY => $this->onWatchList,
        ];
    }
}