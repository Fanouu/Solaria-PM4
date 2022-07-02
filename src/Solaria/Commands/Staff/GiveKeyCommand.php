<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use pocketmine\item\VanillaItems;
use pocketmine\item\ItemFactory;

class GiveKeyCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("givekey", "§o§6Solaria §7» §8Donner des key à un joueur", "/givekey <type> <count> <player>", []);
        $this->setPermission("staff.givekey");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.addmoney")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/givekey <type> <count> <player>");
        if(!isset($args[1])) return $this->errorManager()->noArgs($sender, "/givekey <type> <count> <player>");
        if(!isset($args[2])) return $this->errorManager()->noArgs($sender, "/givekey <type> <count> <player>");
        
        if(!in_array(strtolower($args[0]), ["vote", "saphir", "jade", "rare", "ultime"])) return $this->errorManager()->custom($sender, "§fAucune key trouvé avec cette valeur: §1{$args[0]}");
        if(!is_numeric((int)$args[1])) return $this->errorManager()->custom($sender, "§fMerci de rentrer une valeur exacte: §1IS_NOT_NUMERIC");
        
        $target = Server::getInstance()->getPlayerByPrefix($args[2]);
        if(!$target) return $this->errorManager()->custom($sender, "Le joueur n'est pas en ligne ou le pseudonyme entré est invalide !");
        
        $i = 1;
        while($i <= (int)$args[1]){
            $key = ItemFactory::getInstance()->get(339, 0, 1);
            $key->getNamedTag()->setString("ticketType", strtolower($args[0]));
            $key->setCustomName("Key §6{$args[0]}");
            $target->getInventory()->addItem($key);
            $i++;
        }
        
        $target->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez reçus une §6key §e{$args[0]} §f!");
    }

}