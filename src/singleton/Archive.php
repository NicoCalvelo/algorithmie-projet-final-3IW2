<?php

namespace App\Singleton;

class Archive
{
    private static $instance = null;
    private $history = [];

    private function __construct()
    {
        // Load history from json file
        if (is_dir(__DIR__ . '/../../data') && file_exists(__DIR__ . '/../../data/history.json')) {
            $this->history = json_decode(file_get_contents(__DIR__ . '/../../data/history.json'), true);
        } else {
            $this->history = [];
        }
    }

    public static function getInstance(): Archive
    {
        if (self::$instance === null) {
            self::$instance = new Archive();
        }

        return self::$instance;
    }

    public function addHistory($history)
    {
        $this->history[] = $history;
        $this->saveHistory();
    }

    public function getHistory()
    {
        return $this->history;
    }

    private function saveHistory()
    {
        if(!is_dir(__DIR__ . '/../../data')) {
            mkdir(__DIR__ . '/../../data');
        }

        file_put_contents(__DIR__ . '/../../data/history.json', json_encode($this->history));
    }
}
