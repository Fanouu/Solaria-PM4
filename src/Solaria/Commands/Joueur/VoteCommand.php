<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Core; 
use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

use Solaria\Events\Player\PlayerMove;
use Solaria\Tasks\VoteAsync;

class VoteCommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("vote", "§o§6Solaria §7» §8Voter pour le server", "/vote", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if ($sender instanceof PlayerManager) {
            Core::getInstance()->getServer()->getAsyncPool()->submitTask(new VoteAsync($sender->getName()));
        }
    }

}