<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;

use Solaria\Managers\PlayerManager;

class PlayerCreation implements Listener{
    use PlayerListener;
    
    public function onCreation(PlayerCreationEvent $event){
        $event->setPlayerClass(PlayerManager::class);
    }
}