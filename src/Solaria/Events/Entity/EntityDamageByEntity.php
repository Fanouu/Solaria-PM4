<?php
    
namespace Solaria\Events\Entity;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use Solaria\Events\Player\PlayerListener;
use Solaria\Managers\PlayerManager;

use Solaria\Core;

class EntityDamageByEntity implements Listener{
    use PlayerListener;
    
    public $combatTime = [];
    
    public function onDamage(EntityDamageEvent $event){
        if($event instanceof EntityDamageByEntityEvent){
            /*$event->setKnockBack(0.3); */
            $event->setAttackCooldown(9);
            $entity = $event->getEntity();
            $damager = $event->getDamager();
        
            if($entity instanceof PlayerManager && $damager instanceof PlayerManager){
            
               if($event->isCancelled() === true) return false;

               if($damager->isCreative()) return false;
            
                if(!isset(Core::getInstance()->combatTime[$entity->getName()])){
                    $entity->sendMessage("§o§f[§6§lSolariaLogger§r§o]§r§f Vous rentrée en combat, ne vous §cdéconnecter§f pas !");
                }
            
                if(!isset(Core::getInstance()->combatTime[$damager->getName()])){
                    $damager->sendMessage("§o§f[§6§lSolariaLogger§r§o]§r§f Vous rentrée en combat, ne vous §cdéconnecter§f pas !");
                }
            
                Core::getInstance()->combatTime[$damager->getName()] = time() + 30;
                Core::getInstance()->combatTime[$entity->getName()] = time() + 30;
            }
        }
    }
}