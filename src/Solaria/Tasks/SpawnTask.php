<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use Solaria\Managers\PlayerManager;
use Solaria\Managers\MessageManager;
use Solaria\Events\Player\PlayerMove;

class SpawnTask extends Task{
    
    private $delay;
    private $cancelled;
    
    public function __construct(PlayerManager $player, $delay, bool $cancelled = false){
        $this->player = $player;
        $this->delay = $delay;
        $this->cancelled = PlayerMove::$teleport[$this->player->getName()];
    }
    
    public function onRun(): void{
        
        $this->cancelled = PlayerMove::$teleport[$this->player->getName()];
        
        if(!$this->player->isOnline()){
            $this->stop();
        }
        
        $messageManager = new MessageManager();
        if($this->cancelled === true){
            $this->player->sendPopup($messageManager->getMessage($this->player, "tp_cancel"));
            $this->stop();
        }
        
        if($this->delay > 0){
            $this->delay--;
            $this->player->sendActionBarMessage(str_replace("{time}", $this->delay, $messageManager->getMessage($this->player, "tp_coold")));
        }else if($this->delay <= 0){
            $this->player->sendActionBarMessage($messageManager->getMessage($this->player, "tp_spawn_succes"));
            $this->player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSpawnLocation(), 0, 0);
            $this->stop();
        }
    }

    public function stop(){
        $event = new PlayerMove();
        unset(PlayerMove::$teleport[$this->player->getName()]);
        $this->getHandler()->cancel();
    }
    
    public function cancel(){
        $this->cancelled = true;
    }
}