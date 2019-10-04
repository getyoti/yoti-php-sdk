<?php

namespace Yoti\ShareUrl\Extension;

use Yoti\Util\Validation;

/**
 * Builds a transactional flow Extension.
 */
class TransactionalFlowExtensionBuilder
{
    /**
     * @var mixed
     */
    private $content;

    /**
     * Transactional flow extension type.
     */
    const TRANSACTIONAL_FLOW = 'TRANSACTIONAL_FLOW';

    /**
     * Allows you to provide a non-null object representing the content to be submitted
     * in the TRANSACTIONAL_FLOW extension.
     *
     * @param mixed $content
     */
    public function withContent($content)
    {
        Validation::notNull($content, 'content');
        $this->content = $content;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Extension\Extension
     *   Extension with TRANSACTIONAL_FLOW type
     */
    public function build()
    {
        return new Extension(self::TRANSACTIONAL_FLOW, $this->content);
    }
}
