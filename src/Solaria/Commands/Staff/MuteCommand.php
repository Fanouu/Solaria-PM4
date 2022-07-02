<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class MuteCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("mute", "§o§6Solaria §7» §8Rendre muet un joueur", "/mute <temps:minutes> <player>", []);
        $this->setPermission("staff.mute");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.mute")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/mute <temp:minutes> <player>");
        if(!isset($args[1])) return $this->errorManager()->noArgs($sender, "/mute <temp:minutes> <player>");
        
        if(!is_numeric((int)$args[0])) return $this->errorManager()->custom($sender, "§fMerci de rentrer une valeur exacte: §1IS_NOT_NUMERIC");
        
        $target = Server::getInstance()->getPlayerByPrefix($args[1]);
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        $maria = $this->database();
        $result = $maria->query("SELECT * FROM mutes WHERE `username` = '" . $target->getName() . "'");
        if(!$result->fetchArray() >= 1){
            $maria->query("INSERT INTO mutes (`username`, `time`) VALUES ('".$target->getName()."', '" . time() + (int)$args[0]*60 . "')");
        }else{
            $maria->query("UPDATE mutes SET `time` = '". time() + (int)$args[0]*60 . "' WHERE `username` = '".$target->getName()."'");
        }


        $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien rendu au silence §1{$target->getName()} §fpendant §9{$args[0]} minutes §f!");
        Server::getInstance()->broadcastMessage("§o§f[§6§l!!!§r§o]§r§f Le joueur §1{$target->getName()} §fa été réduit au silence par §e{$sender->getName()} §fpendant §6{$args[0]} minutes §f!");
        $target->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez été réduit au silence par §1{$sender->getName()} §fpendant §9{$args[0]} minutes §f!");
    }

}