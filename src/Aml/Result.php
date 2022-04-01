<?php

declare(strict_types=1);

namespace Yoti\Aml;

use Psr\Http\Message\ResponseInterface;
use Yoti\Exception\AmlException;
use Yoti\Util\Json;

class Result
{
    private const ON_PEP_LIST_KEY = 'on_pep_list';
    private const ON_FRAUD_LIST_KEY = 'on_fraud_list';
    private const ON_WATCH_LIST_KEY = 'on_watch_list';

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
     * @var array<string, mixed>
     */
    private $rawResult;

    /**
     * AmlResult constructor.
     *
     * @param array<string, bool> $result
     * @param ResponseInterface $response
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function __construct(array $result, ResponseInterface $response)
    {
        $this->rawResult = $result;
        $this->setAttributes($response);
    }

    /**
     * Check if user is a politically exposed person.
     *
     * @return bool
     */
    public function isOnPepList(): bool
    {
        return $this->onPepList;
    }

    /**
     * Check if user is on a fraud list.
     *
     * @return bool
     */
    public function isOnFraudList(): bool
    {
        return $this->onFraudList;
    }

    /**
     * Check if user is on a watch list.
     *
     * @return bool
     */
    public function isOnWatchList(): bool
    {
        return $this->onWatchList;
    }

    /**
     * Set attribute values.
     *
     * @param ResponseInterface $response
     *
     * @throws \Yoti\Exception\AmlException
     */
    private function setAttributes(ResponseInterface $response): void
    {
        $result = $this->rawResult;
        // Check if no attribute is missing from the result
        self::checkAttributes($result, $response);

        $this->onPepList = (bool) $result[self::ON_PEP_LIST_KEY];
        $this->onFraudList = (bool) $result[self::ON_FRAUD_LIST_KEY];
        $this->onWatchList = (bool) $result[self::ON_WATCH_LIST_KEY];
    }

    /**
     * Check if all the attributes are included in the result.
     *
     * @param array<string, bool> $result
     * @param ResponseInterface $response
     *
     * @throws \Yoti\Exception\AmlException
     */
    public static function checkAttributes(array $result, ResponseInterface $response): void
    {
        $expectedAttributes = [
            self::ON_PEP_LIST_KEY,
            self::ON_WATCH_LIST_KEY,
            self::ON_WATCH_LIST_KEY,
        ];
        $providedAttributes = array_keys($result);
        $missingAttr = array_diff($expectedAttributes, $providedAttributes);

        // Throw an error if any expected attribute is missing.
        if (count($missingAttr) > 0) {
            throw new AmlException('Missing attributes from the result: ' . implode(',', $missingAttr), $response);
        }
    }

    /**
     * Returns json string of the raw data.
     *
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this->rawResult);
    }
}
