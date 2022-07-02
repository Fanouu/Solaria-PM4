<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

class ScoreboardTask extends Task{
    public static $scoreboard = [];
    
    public function onRun(): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $player){
            $player->sendScoreboard();
        }
    }

}