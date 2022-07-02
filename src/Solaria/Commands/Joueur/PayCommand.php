<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class PayCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("pay", "§o§6Solaria §7» §8Envoyer de l'argent a un joueur", "/pay <money> <player>", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        
        if(!$sender instanceof PlayerManager) return;
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/pay <money> <player>");
        if(!isset($args[1])) return $this->errorManager()->noArgs($sender, "/pay <money> <player>");
        
        if(!is_numeric((int)$args[0])) return $this->errorManager()->custom($sender, "§fMerci de rentrer une valeur exacte: §eIS_NOT_NUMERIC");
        
        $target = Server::getInstance()->getPlayerByPrefix($args[1]);
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        if($sender->myMoney() < (int)$args[0]) return $this->errorManager()->custom($sender, "Vous n´avez pas asser de §emoney §f!");

        $target->addMoney((int)$args[0]);
        $sender->removeMoney((int)$args[0]);

        $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien envoyé  §e{$args[0]}  à §6{$target->getName()}");
        $target->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez reçus  §e{$args[0]}  de §6{$sender->getName()}");
        
    }

}