<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

use Solaria\Events\Entity\EntityDamageByEntity;

use Solaria\Managers\PlayerManager;
use Solaria\Core;

class CommandPreprocess implements Listener{
    use PlayerListener;
    
    private $CommandBlock = ["/spawn", "/f", "/shop", "/kit", "/purif", "/event", "/repair", "/nexus", "/totem", "/koth"];
    
    public function onPreprocess(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $pname = $player->getName();
        $message = $event->getMessage();
        $args = explode(' ', $message);
        if(str_contains($message, "/ ")){
            $event->cancel();
        }
        
        if(str_contains($message, "./")){
            $event->cancel();
        }
        
        if(in_array(strtolower($args[0]), $this->CommandBlock)){
            if(isset(Core::getInstance()->combatTime[$pname])){
                if(Core::getInstance()->combatTime[$pname] - time() > 0){
                    $event->cancel();
                    $player->sendMessage("§o§f[§6§lSolariaLogger§r§o]§r§f §r§fVous ne pouvez pas exécuter de command en combat !");
                }
            }
        }
    }
}