<?php
    
namespace Solaria\Entities;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;

class FloatingText extends Entity{
    
    public static function getNetworkTypeId() : string{ return EntityIds::NPC; }
    
    protected function getInitialSizeInfo() : EntitySizeInfo{ return new EntitySizeInfo(0.1, 0.1); }

    public function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
        $this->setImmobile(true);
        $this->setNameTagAlwaysVisible(true);
        $this->setScale(0.001);
    }
    
   public function attack(EntityDamageEvent $source): void {
        $source->cancel();
    }
    
    public function getName(): string {
        return "FloatingText";
    }
}