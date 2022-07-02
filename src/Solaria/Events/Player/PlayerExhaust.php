<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

use Solaria\Managers\PlayerManager;

class PlayerExhaust implements Listener{
    use PlayerListener;
    
    public function onCreation(PlayerExhaustEvent $event){
        $event->cancel();
    }
}