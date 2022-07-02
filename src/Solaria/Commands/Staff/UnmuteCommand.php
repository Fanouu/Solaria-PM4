<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class UnmuteCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("unmute", "§o§6Solaria §7» §8Redonner la parole à un joueur", "/unmute <player>", []);
        $this->setPermission("staff.mute");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.mute")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/unmute <player>");
        
        $target = Server::getInstance()->getPlayerByPrefix($args[0]);
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        $maria = $this->database();
        $result = $maria->query("SELECT * FROM mutes WHERE `username` = '" . $target->getName() . "'");
        if(!$result->fetchArray() >= 1){
            $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Le joueur cible n'est pas mute !");
        }else{
            $maria->query("DELETE FROM mutes WHERE `username` = '".$target->getName()."'");
        }

        $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien redonner la parole a §1{$target->getName()}");
        
        $target->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous pouvez de nous prendre la parole. unmute par §1{$sender->getName()}");
    }

}