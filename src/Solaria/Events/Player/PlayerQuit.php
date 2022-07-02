<?php
    
namespace Solaria\Events\Player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;

use Solaria\Events\Player\PlayerListener;

use Solaria\Core;

class PlayerQuit implements Listener{
    use PlayerListener;
    
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $event->setQuitMessage("");
        
        Server::getInstance()->broadcastMessage($this->messageManager()->getMessage($player, "player_quit", true));
        
        if(isset(Core::getInstance()->combatTime[$player->getName()])){
            if(Core::getInstance()->combatTime[$player->getName()] - time() > 0){
                $player->kill();
                unset(Core::getInstance()->combatTime[$player->getName()]);
            }
        }
    }
}