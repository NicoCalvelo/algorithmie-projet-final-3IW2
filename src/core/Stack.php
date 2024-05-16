<?php

namespace App;

use App\ChainList;

// Liste de type LIFO
class Stack extends ChainList
{


	public function __construct()
	{
		parent::__construct();
	}

	public function pop(): mixed
	{
		$beforeLast = $this->first;
		if ($beforeLast == null) return null;

		while ($beforeLast->next != $this->last && $beforeLast->next != null) {
			$beforeLast = $beforeLast->next;
		}

		$last = $beforeLast->next;

		if ($last == null) {
			$this->last = null;
			$this->first = null;

			return $beforeLast->value;
		} else {
			$beforeLast->next = null;
			$this->last = $beforeLast;

			return $last->value;
		}
	}
}
