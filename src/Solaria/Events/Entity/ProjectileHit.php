<?php
    
namespace Solaria\Events\Entity;

use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\projectile\EnderPearl;
use pocketmine\event\projectile\Projectile;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

use Solaria\Events\Player\PlayerListener;
use Solaria\Managers\PlayerManager;
use pocketmine\item\PotionType;

class ProjectileHit implements Listener{
    use PlayerListener;
    
    private static $cooldown = [];
    
    public function ProjectileHitEvent(ProjectileHitEvent $event){
        $entity = $event->getEntity();   
        
        if($entity::getNetworkTypeId() === EntityIds::SPLASH_POTION){
            if(!$entity->isAlive()) return;
            $owner = $entity->getOwningEntity();
            if(is_null($owner)) return;
            if($entity->getWorld()->getBlockAt(round($owner->getLocation()->x), round($owner->getLocation()->y)-1, round($owner->getLocation()->z))->getId() === 0){
                $event->getEntity()->teleport($owner->getLocation());
            }

            if($owner->getHealth() < $owner->getMaxHealth()){
                $owner->setHealth($owner->getHealth() + 1);
            }
        }
    }
}