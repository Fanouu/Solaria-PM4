<?php

namespace Solaria\Items;

use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\EnderPearl as PMMPEnderPearl;
use pocketmine\player\Player;
use Solaria\Entities\EnderPearl as SEnderPearl;

class EnderItem extends PMMPEnderPearl{

    /**
     * @param Location $location
     * @param Player $thrower
     * @return Throwable
     */
    protected function createEntity(Location $location, Player $thrower): Throwable
    {
        return new SEnderPearl($location, $thrower);
    }
}