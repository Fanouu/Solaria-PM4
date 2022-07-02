<?php

namespace Solaria\Entities;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\EnderPearl as EP;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\world\particle\EndermanTeleportParticle;
use pocketmine\world\sound\EndermanTeleportSound;
use Solaria\Managers\PlayerManager;
use pocketmine\event\entity\ProjectileHitEvent;

class EnderPearl extends EP{

    /**
     * @param ProjectileHitEvent $event
     * @return void
     */
    protected function onHit(ProjectileHitEvent $event) : void {
        $owner = $this->getOwningEntity();
        if ($owner !== null) {
            $hitVector = $event->getRayTraceResult()->getHitVector();
            if ($this->getWorld()->getBlock(($block = $this->getWorld()->getBlock($hitVector))->getPosition()->add(0, 1, 0))->isSolid() && $this->canGoThrough($block)) {
                if (in_array($event->getRayTraceResult()->getHitFace(), [Facing::DOWN, Facing::UP])) {
                    $yaw = $owner->getLocation()->getYaw();
                    if ($yaw >= 45 && $yaw < 135) {
                        $hitVector = $hitVector->subtract(1, 0, 0);
                    } elseif ($yaw >= 135 && $yaw < 225) {
                        $hitVector = $hitVector->subtract(0, 0, 1);
                    } elseif ($yaw >= 225 && $yaw < 315) {
                        $hitVector = $hitVector->add(1, 0, 0);
                    } else {
                        $hitVector = $hitVector->add(0, 0, 1);
                    }
                    $this->teleportEntity($owner, $hitVector);
                } elseif ($owner instanceof PlayerManager && !$owner->isCreative()) {
                    $owner->getInventory()->addItem(ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL));
                }
            } else {
                $this->teleportEntity($owner, $hitVector);
            }
        }
    }

    /**
     * @param Block $block
     * @return bool
     */
    private function canGoThrough(Block $block) : bool {
        $blocks = ["Slab", "Stair", "EnchantingTable", "Chest", "Fence", "FenceGate", "Bed", "Wall", "EndPortalFrame", "Anvil", "Trapdoor", "DaylightSensor"];
        foreach ($blocks as $blockName) {
            $blockClass = ('pocketmine\block\\' . $blockName);
            if ($block instanceof $blockClass) return true;
        }
        return false;
    }

    /**
     * @param Entity $entity
     * @param Vector3 $vector3
     * @return void
     */
    private function teleportEntity(Entity $entity, Vector3 $vector3) : void {
        $this->getWorld()->addParticle(($origin = $entity->getPosition()), new EndermanTeleportParticle());
        $this->getWorld()->addSound($origin, new EndermanTeleportSound());
        $entity->teleport($vector3);
        $this->getWorld()->addSound($vector3, new EndermanTeleportSound());
        $entity->attack(new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_FALL, 5));
    }

}