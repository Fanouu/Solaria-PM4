<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

use pocketmine\world\Position;

class MinageCommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("minage", "§o§6Solaria §7» §8Ce teleporter au Minage", "/minage", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            $sender->teleport(new Position(6, 192, 5, $sender->getServer()->getWorldManager()->getWorldByName("Mine")));
            $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien été teleporté au §1minage§f !");
        }
        
    }

}