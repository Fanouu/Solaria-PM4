<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class AddPbCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("addpb", "§o§6Solaria §7» §8Ajouter des point boutique à un joueur", "/addpb <point boutique> <player>", []);
        $this->setPermission("staff.addpb");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.addpb")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/addpb <point boutique> <player>");
        if(!isset($args[1])) return $this->errorManager()->noArgs($sender, "/addpb <point boutique> <player>");
        
        if(!is_numeric((int)$args[0])) return $this->errorManager()->custom($sender, "§fMerci de rentrer une valeur exacte: §1IS_NOT_NUMERIC");
        
        $target = Server::getInstance()->getPlayerByPrefix($args[1]);
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        $money = $target->myPointBoutique() + (int)$args[0];
        $maria = $this->database();
        $maria->query("UPDATE player SET `pointboutique` = '".$money."' WHERE `username` = '". $target->getName(). "'");

        $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien définie les §9point boutique §fde §1{$target->getName()} §fpar {$target->myPointBoutique()} §8(+{$args[0]})");
    }

}