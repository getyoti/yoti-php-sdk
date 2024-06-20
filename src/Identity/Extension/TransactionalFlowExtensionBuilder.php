<?php

namespace Yoti\Identity\Extension;

use Yoti\Util\Validation;

/**
 * Builds a transactional flow Extension.
 */
class TransactionalFlowExtensionBuilder implements ExtensionBuilderInterface
{
    private const TYPE = 'TRANSACTIONAL_FLOW';

    private object $content;

    /**
     * Allows you to provide a non-null object representing the content to be submitted
     * in the TRANSACTIONAL_FLOW extension.
     */
    public function withContent(object $content): self
    {
        Validation::notNull($content, 'content');
        $this->content = $content;

        return $this;
    }

    /**
     * @return Extension with TRANSACTIONAL_FLOW type
     */
    public function build(): Extension
    {
        return new Extension(static::TYPE, $this->content);
    }
}
