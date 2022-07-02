<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use Solaria\Managers\PlayerManager;
use Solaria\Managers\MessageManager;

class TPATask extends Task{
    
    public function __construct(PlayerManager $player, PlayerManager $target, $delay){
        $this->player = $player;
        $this->target = $target;
    }
    
    public function onRun(): void{
        
        $messageManager = new MessageManager();
        
        if(!$delay === 0){
            $delay--;
        }else if($delay === 0){
            $this->player->sendMessage($mesageManager->getMessage($this->player, "tp_expired"));
            $this->getHandler()->cancel();
        }
    }

}