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

    public function __construct($onPepList, $onFraudList, $onWatchList)
    {
        $this->onPepList = $onPepList;

        $this->onFraudList = $onFraudList;

        $this->onWatchList = $onWatchList;
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

    public function getResponseArray()
    {
        return [
            self::ON_PEP_LIST_KEY => $this->onPepList,
            self::ON_FRAUD_LIST_KEY => $this->onFraudList,
            self::ON_WATCH_LIST_KEY => $this->onWatchList,
        ];
    }
}