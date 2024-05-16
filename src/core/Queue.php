<?php

namespace App;

use App\ChainList;

// Liste de type FIFO
class Queue extends ChainList
{

    public function __construct()
    {
        parent::__construct();
    }

    public function pop(): mixed
    {
        $element = $this->first;
        if ($element == null) return null;

        $this->first = $this->first->next;

        return $element->value;
    }
}
