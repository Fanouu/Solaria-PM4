<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class KickCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("kick", "§o§6Solaria §7» §8Expulser un joueur du server", "/kick {player}", []);
        $this->setPermission("staff.kick");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.kick")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/kick <player> [raison]");
            
        $target = Server::getInstance()->getPlayerByPrefix($args[0]);
            
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
            
        if($target->getName() === $sender->getName()) return $this->errorManager()->custom($sender, "Vous ne pouvez pas vous auto-kick !");
            
        if(isset($args[1])){
            $target->kick("§fVous avez été kick par §9{$sender->getName()}§f pour:§7" . str_replace($args[0], "", trim(implode(" ", $args))));
        }else{
            $target->kick("§fVous avez été kick par §9{$sender->getName()}");
        }
    }

}