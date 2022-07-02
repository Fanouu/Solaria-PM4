<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Core; 
use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

use Solaria\Events\Player\PlayerMove;
use Solaria\Tasks\SpawnTask;

class SpawnCommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("spawn", "§o§6Solaria §7» §8Ce teleporter au spawn", "/spawn", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if ($sender instanceof PlayerManager) {
            //PlayerMove::$teleport[$sender->getName()] = false;
            //Core::getInstance()->getScheduler()->scheduleRepeatingTask(new SpawnTask($sender, 5), 20);
            $sender->teleport($sender->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
        }
    }

}