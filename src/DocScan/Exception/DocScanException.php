<?php

declare(strict_types=1);

namespace Yoti\DocScan\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class DocScanException extends Exception
{

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * DocScanException constructor.
     * @param string $message
     * @param ResponseInterface|null $response
     * @param Throwable|null $previous
     */
    public function __construct($message = "", ResponseInterface $response = null, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->response = $response;
    }

    /**
     * Returns the HTTP response object returned
     * from the Doc Scan API.
     *
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
