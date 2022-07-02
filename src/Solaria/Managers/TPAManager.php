<?php
    
namespace Solaria\Managers;

use Solaria\Managers\PlayerManager;
use Solaria\Taks\TPATask;

class TPAManager{
    
    public $request = [];
    
    public function createRequest(PlayerManager $player, PlayerManager $target, $types){
        if($this->request[$player->getName()]["TO"] === $target) return "request_exists";
        
        if(isset($this->request[$player->getName()])) return "has_request";
        
        $this->request[$player->getName()] => [
            "TO" => $target->getName(),
            "types" => $types
        ];
        
        Core::getInstance()->getScheduler()->scheduleRepeatingTask(new TPATask($player, $target, 60), 20);
    }
}