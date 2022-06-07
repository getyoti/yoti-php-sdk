<?php

declare(strict_types=1);

namespace Yoti\Exception;

use Psr\Http\Message\ResponseInterface;
use Yoti\Exception\base\YotiException;
use Yoti\Util\Json;

class ActivityDetailsException extends YotiException
{
    /**
     * @var array<string, mixed>|null
     */
    private $responseBody;

    /**
     * @param string $message
     * @param ResponseInterface|null $response
     * @param array<string, mixed>|null $responseBody
     * @param \Throwable|null $previous
     */
    public function __construct(
        $message = "",
        ?ResponseInterface $response = null,
        ?array $responseBody = null,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $response, $previous);

        $this->responseBody = $responseBody;
    }

    /**
     * @return string
     */
    public function getReceiptErrorDetails(): string
    {
        $result = [];
        if (!is_null($this->responseBody)) {
            $result['receipt_id'] = $this->responseBody['receipt']['receipt_id'] ?? ' ';
            if (isset($this->responseBody['error_details'])) {
                $result['description'] = $this->responseBody['error_details']['description'] ?? ' ';
                $result['error_code'] = $this->responseBody['error_details']['error_code'] ?? ' ';
            }
        }
        return Json::encode($result);
    }
}
