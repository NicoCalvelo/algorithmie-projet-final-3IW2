<?php

namespace App;

use App\EnchainableValue;

class ChainList
{

    protected ?EnchainableValue $first;
    protected ?EnchainableValue $last;

    public function __construct()
    {
        $this->first = null;
        $this->last = null;
    }

    public function getFirst(): EnchainableValue | null
    {
        return $this->first;
    }


    public function push(mixed $value)
    {
        $element = new EnchainableValue($value);

        if ($this->first == null) {
            $this->first = $element;
            $this->last = $element;
        } else {
            $this->last->next = $element;
            $this->last = $element;
        }

        $this->last = $element;
    }
}
