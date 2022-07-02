<?php

namespace Solaria\Events\Listener;

use Solaria\Managers\KothManager;
use Solaria\Core;
use Solaria\Tasks\KothStartedTask;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Solaria\Utils\Utils;

class KothListener implements Listener {

    public static $poskoth = [];
    private $plugin;
    
    public function __construct(){
      $this->plugin = Core::getInstance();
      $this->koth_data = new Config($this->plugin->getDataFolder() . "Events/koth.json", Config::JSON);
    }

    public function onMove(PlayerMoveEvent $event) {
      
        if(KothManager::getInstance()->get("koth_enable") == true) {

            $player = $event->getPlayer();
            if(KothManager::getInstance()->get("capturBy") === "undefined") {

                if($player->getWorld()->getFolderName() === "KothInferno") {

                        if(Utils::isInPos($player, "-3:67:3", "3:64:-3", "KothInferno")) {
                            KothManager::getInstance()->set("capturBy", $player->getName());
                            $player->getServer()->broadcastPopup("§r§1{$player->getName()} §fcommence a capturé le §cKOTH §f!");

                        }

                }

            } else {
                if(!KothManager::getInstance()->get("capturBy") === $player->getName()){
                    $capturBy = KothManager::getInstance()->get("capturBy");
                    $player->sendPopup("§1$capturBy §r§fest déjà en train de capturer le §cKOTH§f, §9éjecte §fle de la plateforme !");
               }

                if (!Utils::isInPos($player, "-3:67:3", "3:64:-3", "KothInferno")) {

                    if (KothManager::getInstance()->get("capturBy") === $player->getName()) {        
                        KothManager::getInstance()->set("capturBy", "undefined");
                        KothStartedTask::$number  = 0;
                    }

                }

            }

        }

    }
    
    public function onQuit(PlayerQuitEvent $event){
        $player  = $event->getPlayer();
        
       if (KothManager::getInstance()->get("capturBy") === $player->getName()) {                  
           KothManager::getInstance()->set("capturBy", "undefined");
           KothStartedTask::$number  = 0;
       }
    }

}