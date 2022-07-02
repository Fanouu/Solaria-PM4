<?php

namespace Solaria\Thread;

use pocketmine\snooze\SleeperNotifier;
use pocketmine\thread\Thread;

class SQLThread extends Thread
{

    /**
     * @var SleeperNotifier
     */
    private  SleeperNotifier $notifier;

    public function __construct(SleeperNotifier $notifier, callable $call){
        $this->notifier = $notifier;
        $this->call = $call;
        $this->start(PTHREADS_INHERIT_INI | PTHREADS_INHERIT_CONSTANTS);
    }

    protected function onRun(): void
    {
        while (true){
            $this->notifier->wakeupSleeper();
        }
    }


    public function getThreadName(): string
    {
        return "SQLThread";
    }
}