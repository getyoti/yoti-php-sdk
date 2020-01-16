<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

class MultiValue extends \ArrayObject
{
    /**
     * @var array Original unfiltered Items.
     */
    private $originalItems = [];

    /**
     * @var boolean Flag to make MultiValue immutable.
     */
    private $isImmutable = false;

    /**
     * @var callable[] Filter callbacks.
     */
    private $filters = [];

    /**
     * MultiValue Constructor.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->originalItems = array_values($items);
        parent::__construct($this->originalItems);
    }

    /**
     * Filters Items using callback.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function filter(callable $callback): self
    {
        $this->assertMutable('Attempting to filter immutable array');
        $this->filters[] = $callback;
        $this->applyFilters();
        return $this;
    }

    /**
     * Apply all filters.
     */
    private function applyFilters(): void
    {
        // Only apply filters if this is mutable.
        if ($this->isImmutable) {
            return;
        }

        // Reset to original items before filtering.
        $this->exchangeArray($this->originalItems);

        // Apply filter to items.
        if (count($this->filters) > 0) {
            $filtered_array = array_filter(
                $this->getArrayCopy(),
                function ($item) {
                    foreach ($this->filters as $callback) {
                        if (call_user_func($callback, $item) === true) {
                            return true;
                        }
                    }
                    return false;
                }
            );
            $this->exchangeArray(array_values($filtered_array));
        }

        // Filter nested items.
        foreach ($this->getArrayCopy() as $item) {
            if ($item instanceof MultiValue) {
                foreach ($this->filters as $callback) {
                    $item->filter($callback);
                }
            }
        }
    }

    /**
     * Filter items by instance.
     *
     * @param string $type
     *
     * @return $this
     */
    public function allowInstance(string $type): self
    {
        return $this->filter(function ($item) use ($type) {
            return $item instanceof $type;
        });
    }

    /**
     * Filter items by type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function allowType(string $type): self
    {
        return $this->filter(function ($item) use ($type) {
            return gettype($item) === $type;
        });
    }

    /**
     * Make this MultiValue Immutable.
     *
     * @return $this
     */
    public function immutable(): self
    {
        $this->isImmutable = true;

        // Lock nested items.
        foreach ($this->getArrayCopy() as $item) {
            if ($item instanceof MultiValue) {
                $item->immutable();
            }
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function append($value): void
    {
        $this->assertMutable('Attempting to append to immutable array');
        parent::append($value);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function exchangeArray($input): void
    {
        $this->assertMutable('Attempting to change immutable array');
        parent::exchangeArray($input);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function offsetSet($index, $newval): void
    {
        $this->assertMutable('Attempting to add to immutable array');
        parent::offsetSet($index, $newval);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function offsetUnset($index): void
    {
        $this->assertMutable('Attempting to remove from immutable array');
        parent::offsetUnset($index);
    }

    /**
     * Asserts that this MultiValue is mutable.
     *
     * @param string $message
     *   Used as Exception message when immutable.
     *
     * @throws \LogicException
     */
    private function assertMutable(string $message)
    {
        if ($this->isImmutable) {
            throw new \LogicException($message);
        }
    }
}
