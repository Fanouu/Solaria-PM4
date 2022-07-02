<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

use Solaria\Events\Entity\EntityDamageByEntity;

use Solaria\Managers\PlayerManager;
use Solaria\Core;
use Solaria\Utils\Utils;

class PlayerChat implements Listener{
    use PlayerListener;
    
    private static $coold = [];
    
    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $pname = $player->getName();
        if(!$player->isMuted() === false){
            $time = Utils::convertTime($player->isMuted()[1]);
            if($player->isMuted()[1] - time() > 0){
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous etes réduit au silence. Pendant §e{$time["hours"]}h§f, §e{$time["minuts"]}m§f, §e{$time["seconds"]}s");
                $event->cancel();
            }else{
                $this->database()->query("DELETE FROM mutes WHERE `username` = '".$pname."'");
            }
        }
        if(isset(self::$coold[$pname]) && self::$coold[$pname] - time() > 0){
            $event->cancel();
            $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Éh Oh... §ftu écrit trop vite !");
        }else{
            self::$coold[$pname] = time() + 5;
        }
    }
}