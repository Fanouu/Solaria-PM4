<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class TPACommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("tpa", "» §1Solaria §7TPA", "/tpa <player>", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if ($sender instanceof PlayerManager) {
            if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/tpa <player>");
            
            $target = Server::getInstance()->getPlayerByPrefix($args[0]);
            if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        }
    }

}