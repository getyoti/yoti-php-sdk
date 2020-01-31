<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class BreakdownResponse
{

    /**
     * @var string|null
     */
    private $subCheck;

    /**
     * @var string|null
     */
    private $result;

    /**
     * @var DetailsResponse[]
     */
    private $details = [];

    /**
     * BreakdownResponse constructor.
     * @param array<string, mixed> $breakdown
     */
    public function __construct(array $breakdown)
    {
        $this->subCheck = $breakdown['sub_check'] ?? null;
        $this->result = $breakdown['result'] ?? null;

        if (isset($breakdown['details'])) {
            foreach ($breakdown['details'] as $detail) {
                $this->details[] = new DetailsResponse($detail['name'], $detail['value']);
            }
        }
    }

    /**
     * @return string|null
     */
    public function getSubCheck(): ?string
    {
        return $this->subCheck;
    }

    /**
     * @return string|null
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @return DetailsResponse[]
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }
}
