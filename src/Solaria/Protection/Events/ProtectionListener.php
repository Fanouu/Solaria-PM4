<?php
    
namespace Solaria\Protection\Events;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use Solaria\Protection\Protection;
use Solaria\Utils\Utils;
use Solaria\Managers\PlayerManager;

class ProtectionListener implements Listener{
    
    public function onBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $world = $player->getWorld()->getFolderName();
        
        if($world === "Minage"){
            if($event->getBlock()->getId() === 153){
                return;
            }
            
            $event->cancel();
        }
        
        if($world === "KothInferno" || $world === "KothMEDIEVAL"){
            if($event->getBlock()->getId() === 42){
                return;
            }
            $event->cancel();
        }
        
        if(Protection::blockIsInZone($block, "191:0:191", "-192:0:-192", $player, "KITMAP")){
            if(!$player->getServer()->isOp($player->getName())){
                $event->cancel();
            }
        }
    }
    
    public function onPlace(BlockPlaceEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $world = $player->getWorld()->getFolderName();
        
        if($world === "KothInferno" || $world === "KothMEDIEVAL" || $world === "Minage"){
            $event->cancel();
        }
        
        if(Protection::blockIsInZone($block, "191:0:191", "-192:0:-192", $player, "KITMAP")){
            if(!$player->getServer()->isOp($player->getName())){
                $event->cancel();
            }
        }
    }
    
    public function onDamage(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $damager = $event->getDamager();
        if(!$player instanceof PlayerManager) return;
        
        if(Utils::isInPos($player, "-76:66:-75", "74:255:75", "KITMAP")){
            $event->cancel();
            return true;
        }
        
        if(Protection::entityIsInZone($damager, "191:0:191", "-192:0:-192", "KITMAP")){
            $event->uncancel();
            return true;
        }
    }
}