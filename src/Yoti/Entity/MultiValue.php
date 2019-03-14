<?php
namespace Yoti\Entity;

class MultiValue extends \ArrayObject
{
    /**
     * @var array Original unfiltered Items.
     */
    private $originalItems = [];

    /**
     * @var callable[] Filter callbacks.
     */
    private $filters = [];

    /**
     * MultiValue Constructor.
     *
     * @param array $items
     */
    public function __construct($items)
    {
        $this->originalItems = array_values($items);
        parent::__construct($items);
    }

    /**
     * Filters Items using callback.
     *
     * @param callable $callback
     * @return MultiValue
     */
    public function filter($callback)
    {
        $this->filters[] = $callback;
        $this->applyFilters();
        return $this;
    }

    /**
     * Apply all filters.
     */
    private function applyFilters()
    {
        // Reset to original items before filtering.
        $this->exchangeArray($this->originalItems);

        // Apply filter to items.
        if (count($this->filters) > 0) {
            $filtered_array = array_filter(
                $this->getArrayCopy(),
                [$this, 'filterItem']
            );
            $this->exchangeArray(array_values($filtered_array));
        }

        // Filter nested items.
        foreach ($this->getArrayCopy() as $item) {
            if ($item instanceof MultiValue) {
                $item->resetFilters();
                foreach ($this->filters as $callback) {
                    $item->filter($callback);
                }
            }
        }
    }

    /**
     * Callback for array_filter().
     *
     * @param mixed $item
     * @return boolean
     */
    private function filterItem($item)
    {
        foreach ($this->filters as $callback) {
            if (call_user_func($callback, $item) === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * Filter items by instance.
     *
     * @param string $type
     *
     * @return MultiValue
     */
    public function filterInstance($type)
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
     * @return MultiValue
     */
    public function filterType($type)
    {
        return $this->filter(function ($item) use ($type) {
            return gettype($item) === $type;
        });
    }

    /**
     * Resets items to original values.
     *
     * @return MultiValue
     */
    public function resetFilters()
    {
        $this->filters = [];
        $this->applyFilters();
        return $this;
    }
}