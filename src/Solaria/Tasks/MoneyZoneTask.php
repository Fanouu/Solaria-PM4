<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use Solaria\Managers\PlayerManager;
use Solaria\Managers\MessageManager;
use Solaria\Events\Player\PlayerMove;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use Solaria\Utils\Provider;
use Solaria\Utils\Utils;

class MoneyZoneTask extends Task{
    
    private $delay;
    private $player;
    private $pname;
    
    public function __construct(PlayerManager $player, string $pname, int $delay){
        $this->player = $player;
        $this->delay = $delay;
        $this->pname = $pname;
    }
    
    public function onRun(): void{
        if(!$this->player->isConnected()){
            $this->getHandler()->cancel();
            return;
        }
        
        if(!Utils::isInPos($this->player, "-8:61:28", "-10:64:30", "kitmap")){
            $this->getHandler()->cancel();
            return;
        }else{
            if($this->delay < 6){
                $this->sendBar();
                $this->delay++;
            }else if($this->delay > 5){
                $rdm = 5;
                $money = $this->player->myMoney() + $rdm;
                $maria = Provider::database();
                $maria->query("UPDATE player SET `money` = '".$money."' WHERE `username` = '". $this->pname. "'");
                $this->player->sendActionBarMessage("§7» §freçus §9+$rdm ");
                $this->delay = 0;
            }
        }
    }
    
    public function sendBar(){
        $delay = $this->delay;
        if($delay === 0){
            $this->player->sendPopup("§7» §fPréparation §c■■■■■");
        }
        if($delay === 1){
            $this->player->sendPopup("§7» §fPréparation §2■§c■■■■");
        }
        if($delay === 2){
            $this->player->sendPopup("§7» §fPréparation §2■■§c■■■");
        }
        if($delay === 3){
            $this->player->sendPopup("§7» §fPréparation §2■■■§c■■");
        }
        if($delay === 4){
            $this->player->sendPopup("§7» §fPréparation §2■■■■§c■");
        }
        if($delay === 5){
            $this->player->sendPopup("§7» §fPréparation §2■■■■■");
        }
    }
}