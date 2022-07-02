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

class PurifTask extends Task{
    
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

        if(!Utils::isInPos($this->player, "-32:69:86", "-27:73:81", "kitmap")){
            $this->getHandler()->cancel();
            return;
        }else{
            $item = $this->player->getInventory()->getItemInHand();
            if($item->getId() === 266){
                if($this->delay < 11){
                    $this->sendBar();
                    $this->delay++;
                }else if($this->delay > 10){
                    $rdm = mt_rand(10, 20);
                    $this->player->getInventory()->removeItem(ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT, 0, 1));
                    $money = $this->player->myMoney() + $rdm;
                    $maria = Provider::database();
                    $maria->query("UPDATE player SET `money` = '".$money."' WHERE `username` = '". $this->pname. "'");
                    $this->player->sendActionBarMessage("§7» §fPurifié §e1§f reçus §6+$rdm ");
                    $this->delay = 0;
                }
            }else{
                $this->player->sendTip("Merci de mettre du §1saphir §fen main !");
                if($this->delay > 0){
                    $this->delay = 0;
                }
            }
        }
    }
    
    public function sendBar(){
        $delay = $this->delay;
        if($delay === 0){
            $this->player->sendPopup("§7» §fPurification §c■■■■■■■■■■");
        }
        if($delay === 1){
            $this->player->sendPopup("§7» §fPurification §2■§c■■■■■■■■■");
        }
        if($delay === 2){
            $this->player->sendPopup("§7» §fPurification §2■■§c■■■■■■■■");
        }
        if($delay === 3){
            $this->player->sendPopup("§7» §fPurification §2■■■§c■■■■■■■");
        }
        if($delay === 4){
            $this->player->sendPopup("§7» §fPurification §2■■■■§c■■■■■■");
        }
        if($delay === 5){
            $this->player->sendPopup("§7» §fPurification §2■■■■■§c■■■■■");
        }
        if($delay === 6){
            $this->player->sendPopup("§7» §fPurification §2■■■■■■§c■■■■");
        }
        if($delay === 7){
            $this->player->sendPopup("§7» §fPurification §2■■■■■■■§c■■■");
        }
        if($delay === 8){
            $this->player->sendPopup("§7» §fPurification §2■■■■■■■■§c■■");
        }
        if($delay === 9){
            $this->player->sendPopup("§7» §fPurification §2■■■■■■■■■§c■");
        }
        if($delay === 10){
            $this->player->sendPopup("§7» §fPurification §2■■■■■■■■■■");
        }
    }
}