<?php
    
namespace Solaria\Managers;

use Solaria\Managers\PlayerManager;
use pocketmine\console\ConsoleCommandSender;

class ErrorManager{
    
    public function noPerms(PlayerManager|ConsoleCommandSender $player, string $permissions = null){
        $messages = $permissions === null ? "§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas les permissions requise !" : "§o§f[§6§l!!!§r§o]§r§f Vous devez possedez la permission suivante: §7$permissions";
        
        return $player->sendMessage($messages);
    }
    
    public function noArgs(PlayerManager|ConsoleCommandSender $player, string $args){
        $messages = "§o§f[§6§l!!!§r§o]§r§f Usage: §7$args";
        
        return $player->sendMessage($messages);
    }
    
    public function custom(PlayerManager|ConsoleCommandSender $player, string $value){
        $messages = "§o§f[§6§l!!!§r§o]§r§f $value";
        
        return $player->sendMessage($messages);
    }
    
    public function smallCooldown(PlayerManager $player, int $time, int $type = 0, string $message = "Veillez patientez §9{time}"){
        $message = str_replace("{time}", $time, $message);
        
        if($type == 0) return $player->sendPopup($message);
        if($type == 1) return $player->sendMessage($message);
        if($type == 2) return $player->sendActionBarMessage($message);
        if($type == 3) return $player->sendTip($message);
    }
}