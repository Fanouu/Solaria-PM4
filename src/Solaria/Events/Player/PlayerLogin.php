<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\Server;

use Solaria\Managers\MessageManager;
use Solaria\Utils\Utils;
use Solaria\Utils\Provider;

class PlayerLogin implements Listener{
    use PlayerListener;
    
    public function PlayerLoginEvent(PlayerLoginEvent $event){
        $player = $event->getPlayer();
        if($player->isBanned() !== false){
            if((int)$player->isBanned()[4] - time() > 0){
                $time = Utils::convertTime((int)$player->isBanned()[4]);
                $reason = $player->isBanned()[5];
                $staff = $player->isBanned()[6];
                $player->kick("§6» §fVous avez été banni du server pour §e$reason\n§e» §fpendant §7" . $time["day"] . "§fd §7" . $time["hours"] . "§fh §7" . $time["minuts"] . "§fm\n§e» §fPar: §7$staff\n§o§cSi ceci est-une erreur merci de venir sur notre support discord");
            }else if((int)$player->isBanned()[4] - time() <= 0){
                $maria = Provider::database();
                $result = $maria->query("DELETE FROM bans WHERE `username` = '". $player->getName()."'");
            }
        }
    }
}