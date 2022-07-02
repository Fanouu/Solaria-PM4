<?php

namespace Solaria\Tasks;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use Solaria\Core;
use pocketmine\utils\Config;
use Solaria\Utils\Utils;

use Solaria\Managers\KothManager;

class KothTask extends Task{

    private $plugin;

    public function __construct(){
        $this->plugin = Core::getInstance();
        $this->koth_data = new Config($this->plugin->getDataFolder() . "Events/koth.json", Config::JSON);
    }

    public function onRun(): void{
        $timer = intval($this->koth_data->get("koth_nexTime") - time());
          $heures = KothManager::getInstance()->getRestantTime("h");
        
        $minutes = KothManager::getInstance()->getRestantTime("m");
        $sec = KothManager::getInstance()->getRestantTime("s");
        

        $timeKoth = KothManager::getInstance()->get("koth_nexTime");
        if(!$timeKoth or($timeKoth <= 0)){
            KothManager::getInstance()->set("koth_nexTime", time() + 3*60*60);
            
            //$this->plugin->getKothAPI()->set("koth_nexTime", time() + 30);
           
        }
        
        if($heures == 0){
        if($minutes === 45 and($sec == 50)){
            Server::getInstance()->broadcastMessage("§1[§9§l!!!§r§1] §fL'événement Koth est disponible dans §c45 minutes §f!");
        }

        if($minutes === 30 and($sec == 50)){
            Server::getInstance()->broadcastMessage("§1[§9§l!!!§r§1] §fL'événement Koth est disponible dans §c30 minutes §f!");
        }

        if($minutes === 15 and($sec == 50)){
            Server::getInstance()->broadcastMessage("§1[§9§l!!!§r§1] §fL'événement Koth est disponible dans §c15 minutes §f!");
        }

        if($minutes == 0 and(KothManager::getInstance()->get("koth_enable") == false)){
            KothManager::getInstance()->start();
        }
        }


    }
}