<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use Solaria\Core;

class SolariaLoggerTask extends Task{

    public function onRun(): void{
        
        $serv = Core::getInstance()->getServer();
        foreach(Core::getInstance()->combatTime as $index => $t){
            if($t - time() <= 0){
                $player = $serv->getPlayerExact($index);
                if($player){
                    $player->sendMessage("§o§f[§6§lSolariaLogger§r§o]§r§f §r§fVous n'êtes plus en combat !");
                }
                if(isset(Core::getInstance()->combatTime[$player->getName()])){
                    unset(Core::getInstance()->combatTime[$player->getName()]);
                }
            }
        }
    }

}