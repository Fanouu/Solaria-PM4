<?php

namespace Solaria\Items;

use pocketmine\entity\Location;
use pocketmine\item\Egg as PMEgg;
use pocketmine\entity\projectile\Throwable;
use pocketmine\player\Player;
use Solaria\Entities\EggEntity as SEgg;

class Egg extends PMEgg{

    /**
     * @param Location $location
     * @param Player $thrower
     * @return Throwable
     */
    protected function createEntity(Location $location, Player $thrower): Throwable
    {
        return new SEgg($location, $thrower);
    }
}