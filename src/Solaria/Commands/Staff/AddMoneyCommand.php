<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class AddMoneyCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("addmoney", "§o§6Solaria §7» §8Ajouter de l'argent à un joueur", "/addmoney <money> <player>", []);
        $this->setPermission("staff.addmoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.addmoney")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/addmoney <money> <player>");
        if(!isset($args[1])) return $this->errorManager()->noArgs($sender, "/addmoney <money> <player>");
        
        if(!is_numeric((int)$args[0])) return $this->errorManager()->custom($sender, "§fMerci de rentrer une valeur exacte: §1IS_NOT_NUMERIC");
        
        $target = Server::getInstance()->getPlayerByPrefix($args[1]);
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        $target->addMoney((int)$args[0]);

        $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien définie la §6money §fde §e{$target->getName()} §fpar {$target->myMoney()} §8(+{$args[0]})");
    }

}