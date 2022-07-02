<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use Solaria\Core;

class UpdateNetwork extends Task{

    public static $counter = 0;
    public static $time = 0;
    public static $networkName = ["§6Solaria§eMc!", "§dPvP Pot", "§bEvent §3KOTH§f, §3Nexus§f... !"];

    public function onRun(): void{

        if(self::$time === 7){
            $serv = Core::getInstance()->getServer();
            $serv->getNetwork()->setName(self::$networkName[self::$counter]);

            self::$counter++;
            if(self::$counter == count(self::$networkName)){
                self::$counter = 0;
            }
            self::$time = 0;

        }
        self::$time++;
    }

}