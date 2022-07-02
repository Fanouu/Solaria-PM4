<?php

namespace Solaria\Tasks;

use pocketmine\entity\ExperienceManager;
use pocketmine\entity\object\ItemEntity;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use Solaria\Entities\JadeBox;
use Solaria\Entities\SaphirBox;
use Solaria\Entities\VoteBox;
use Solaria\Managers\PlayerManager;

class ClearLaggTask extends Task {

    public static $time = 0;

    public function __construct() {
        self::$time = 80;
    }

    public function onRun() : void {
        if(self::$time > 0){
            self::$time--;
            if(in_array(self::$time, [60, 30, 5, 3, 2, 1])){
                Server::getInstance()->broadcastTip("§o§f[§6§l!!!§r§o]§r§f §6ClearLagg §fdans §e" . self::$time . " secondes §f!");
            }
        }else if(self::$time <= 0){
            self::$time = 15*60;

            $count = 0;
            foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world){
                foreach ($world->getEntities() as $entity){
                    if($entity instanceof ItemEntity || $entity instanceof ExperienceManager){
                        $entity->flagForDespawn();
                        $entity->close();
                        $count++;
                    }
                }
            }

            Server::getInstance()->broadcastTip("§o§f[§6§l!!!§r§o]§r§f §e$count entity§f clear");
        }
    }

}