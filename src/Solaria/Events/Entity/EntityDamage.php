<?php
    
namespace Solaria\Events\Entity;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use Solaria\Events\Player\PlayerListener;
use Solaria\Managers\PlayerManager;

class EntityDamage implements Listener{
    use PlayerListener;
    
    
    public function onDamage(EntityDamageEvent $event){
        if($event->getCause() === EntityDamageEvent::CAUSE_FALL){
            $event->cancel();
        }
        
        if($event->getEntity()->getWorld()->getFolderName() === "Mine"){
            $event->cancel();
        }
    }
}