<?php

namespace App;

class EnchainableValue {
    public mixed $value;
    public ?EnchainableValue $next;

    public function __construct($value) {
        $this->value = $value;
        $this->next = null;
    }
}