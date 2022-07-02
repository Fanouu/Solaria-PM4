<?php
    
namespace Solaria\Events\Entity;

use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\projectile\EnderPearl;
use pocketmine\event\projectile\Projectile;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

use Solaria\Events\Player\PlayerListener;
use Solaria\Managers\PlayerManager;

class ProjectileLaunch implements Listener{
    use PlayerListener;
    
    private static $cooldown = [];
    
    public function onLaunch(ProjectileLaunchEvent $event){
        $entity = $event->getEntity();
        
        $owner = $entity->getOwningEntity();
        if($entity::getNetworkTypeId() === EntityIds::ENDER_PEARL){
            if($owner instanceof PlayerManager){
                if(!isset(self::$cooldown[$owner->getName()]) || self::$cooldown[$owner->getName()] - time() <= 0){
                    $event->uncancel();
                    self::$cooldown[$owner->getName()] = time() + 15;
                }else if(self::$cooldown[$owner->getName()] > 0){
                    $event->cancel();
                    $this->errorManager()->smallCooldown($owner, self::$cooldown[$owner->getName()] - time(), 2, "§f§o[§6!!!§f]§r§f Vous devez attendre §e{time} §favant de pearl !");
                }
            }
        }
        
        if($entity::getNetworkTypeId() === EntityIds::SPLASH_POTION){
            if($owner->getWorld()->getBlockAt(round($owner->getLocation()->x), round($owner->getLocation()->y)-1, round($owner->getLocation()->z))->getId() === 0){
                $event->getEntity()->teleport($owner->getLocation()->add(0, 2.9, 0));
            }
        }
    }
}