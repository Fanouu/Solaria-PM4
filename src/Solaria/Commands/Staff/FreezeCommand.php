<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class FreezeCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("freeze", "§o§6Solaria §7» §8Immobiliser un joueur", "/freeze <player>", []);
        $this->setPermission("staff.freeze");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.freeze")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/mute <player>");
        
        if(!is_numeric((int)$args[0])) return $this->errorManager()->custom($sender, "§fMerci de rentrer une valeur exacte: §1IS_NOT_NUMERIC");
        
        $target = Server::getInstance()->getPlayerByPrefix($args[1]);
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        $maria = $this->database();
        $result = $maria->query("SELECT * FROM mutes WHERE `username` = '" . $target->getName() . "'");
        if(!$result->num_rows >= 1){
            $maria->query("INSERT INTO mutes (`username`, `time`) VALUES ('".$target->getName()."', '" . time() + $args[0]*60 . "')");
        }else{
            $maria->query("UPDATE mutes SET `time` = '". time() + $args[0]*60 . "' WHERE `username` = '".$target->getName()."'");
        }

        $sender->sendMessage("§1§l[§9!!!§1] §r§fVous avez bien rendu au silence §1{$target->getName()} §fpendant §9{$args[0]} minutes §f!");
        
        $target->sendMessage("§1§l[§9!!!§1] §r§fVous avez été réduit au silence par §1{$sender->getName()} §fpendant §9{$args[0]} minutes §f!");
    }

}