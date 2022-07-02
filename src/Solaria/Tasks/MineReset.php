<?php

namespace Solaria\Tasks;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class MineReset extends Task {

    public function onRun() : void {
        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), "mine reset-all");
        Server::getInstance()->broadcastTip("Toutes les §6mines §font été reset !");
    }

}