<?php

namespace Yoti\Identity\Extension;

use stdClass;
use Yoti\Util\Validation;

class Extension implements \JsonSerializable
{
    private string $type;

    /**
     * @var mixed
     */
    private $content;

    /**
     * @param mixed $content
     */
    public function __construct(string $type, $content)
    {
        $this->type = $type;

        Validation::notNull($type, 'content');
        $this->content = $content;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object)[
            'type' => $this->type,
            'content' => $this->content,
        ];
    }
}
