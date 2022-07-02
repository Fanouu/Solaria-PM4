<?php
    
namespace Solaria\Protection;

use Solaria\Core;
use Solaria\Protection\Events\ProtectionListener;

use pocketmine\entity\Entity;

class Protection{
    
    public static function startProtection(){
        Core::getInstance()->getServer()->getPluginManager()->registerEvents(new ProtectionListener(), Core::getInstance());
    }
    
    public static function blockIsInZone($block, $posOne, $posTwo, $player, $world){
        $pos1 = explode(":", $posOne);
        $pos2 = explode(":", $posTwo);

        $minX = min($pos1[0], $pos2[0]);
        $maxX = max($pos1[0], $pos2[0]);
        $minY = min($pos1[1], $pos2[1]);
        $maxY = max($pos1[1], $pos2[1]);
        $minZ = min($pos1[2], $pos2[2]);
        $maxZ = max($pos1[2], $pos2[2]);

        if($block->getPosition()->x >= $minX && $block->getPosition()->x <= $maxX && $block->getPosition()->z >= $minZ && $block->getPosition()->z <= $maxZ) {
            if($player->getWorld()->getFolderName() === $world){
              return true;  
            } else return false;

        } else return false;
    }
    
    public static function entityIsInZone(Entity $entity, string $posOne, string $posTwo, string $world) {

        $pos1 = explode(":", $posOne);
        $pos2 = explode(":", $posTwo);

        $minX = min($pos1[0], $pos2[0]);
        $maxX = max($pos1[0], $pos2[0]);
        $minY = min($pos1[1], $pos2[1]);
        $maxY = max($pos1[1], $pos2[1]);
        $minZ = min($pos1[2], $pos2[2]);
        $maxZ = max($pos1[2], $pos2[2]);

        if($entity->getLocation()->x >= $minX && $entity->getLocation()->x <= $maxX && $entity->getLocation()->z >= $minZ && $entity->getLocation()->z <= $maxZ) {
            if($entity->getWorld()->getFolderName() === $world){
              return true;  
            } else return false;

        } else return false;
    }
}