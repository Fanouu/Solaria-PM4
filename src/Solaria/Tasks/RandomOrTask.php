<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use Solaria\Managers\PlayerManager;
use Solaria\Managers\MessageManager;
use pocketmine\world\World;
use pocketmine\world\Position;
use pocketmine\block\BlockFactory;
use pocketmine\math\Vector3;

class RandomOrTask extends Task{
    
    public function __construct(Position $pos, World $world, $delay = 3600){
        $this->pos = $pos;
        $this->world = $world;
        $this->delay = $delay;
        $world->setBlock(new Vector3($pos->x, $pos->y, $pos->z), BlockFactory::getInstance()->get(7, 0));
    }
    
    public function onRun(): void{
        $delay = $this->delay;
        $world = $this->world;
        $x = $this->pos->x;
        $y = $this->pos->y;
        $z = $this->pos->z;
        if($delay > 0){
            $this->delay--;
        }else if($delay <= 0){
            $world->setBlock(new Vector3($x, $y, $z), BlockFactory::getInstance()->get(153, 0));
            $this->getHandler()->cancel();
        }
    }

}