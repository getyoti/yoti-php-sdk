<?php

declare(strict_types=1);

namespace Yoti\Exception;

use Psr\Http\Message\ResponseInterface;
use Yoti\Exception\base\YotiException;
use Yoti\Profile\Receipt;
use Yoti\Util\Json;

class ActivityDetailsException extends YotiException
{
    /**
     * @var Receipt|null
     */
    private $receipt;

    /**
     * @param string $message
     * @param ResponseInterface|null $response
     * @param Receipt|null $receipt
     * @param \Throwable|null $previous
     */
    public function __construct(
        $message = "",
        ?ResponseInterface $response = null,
        ?Receipt $receipt = null,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $response, $previous);

        $this->receipt = $receipt;
    }

    /**
     * @return string
     */
    public function getReceiptErrorDetails(): string
    {
        if (!is_null($this->receipt)) {
            return Json::encode([
                'receipt_id' => $this->receipt->getReceiptId(),
                'description' => $this->receipt->getErrorDetails()['description'] ?? ' ',
                'error_code' => $this->receipt->getErrorDetails()['error_code'] ?? ' ',
            ]);
        }

        return " ";
    }
}
