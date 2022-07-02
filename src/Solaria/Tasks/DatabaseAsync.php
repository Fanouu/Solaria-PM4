<?php

namespace Solaria\Tasks;

use pocketmine\scheduler\AsyncTask;

class DatabaseAsync extends AsyncTask{

    /**
     * @var callable
     */
    private $call1;
    private $call2;

    public function __construct(callable $call1, callable $call2) {
        $this->call1 = $call1;
        $this->call2 = $call2;
    }

    public function onRun(): void
    {
        call_user_func($this->call1, $this);
    }

    public function onCompletion(): void
    {
        call_user_func($this->call2, $this);
    }
}