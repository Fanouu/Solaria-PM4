<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class MsgCommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("msg", "§o§6Solaria §7» §8Envoyez un message privé a un joueur", "/msg <player> <message>", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/msg <player> <messages>");
        $target = Server::getInstance()->getPlayerByPrefix($args[0]);
        
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        if($target->getName() === $sender->getName()) return $this->errorManager()->custom($sender, "§fVous ne pouvez pas vous envoyé un §emsg §fà vous même !");
        
        if(!isset($args[1])) return $this->errorManager()->noArgs($sender, "/msg <player> <messages>");
        $message = str_replace($args[0], "", trim(implode(" ", $args)));
        
        foreach(Server::getInstance()->getOnlinePlayers() as $online){
            if($online->hasPermission("staff.showmsg")){
                $online->sendMessage("§eMSG §fde §6" . $sender->getName() . " §fà §6" . $target->getName() . " §fcontenue:§7$message");
            }
        }
        
        $sender->sendMessage("§e[§6Moi§e] §6-> §e[§6" . $target->getName() . "§e] §f:§7$message");
        $target->sendMessage("§e[§6" . $sender->getName() . "§e] §6-> §e[§6Moi§e] §f:§7$message");
        
    }

}