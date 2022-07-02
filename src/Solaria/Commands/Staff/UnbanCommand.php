<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

class UnbanCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("unban", "§o§6Solaria §7» §8Débannir un joueur", "/unban <player>", []);
        $this->setPermission("staff.ban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!$sender->hasPermission("staff.ban")) return $this->errorManager()->noPerms($sender);
            
        if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/unban <player>");
        
        $maria = $this->database();
        $result = $maria->query("SELECT * FROM bans WHERE `username` = '" . $args[0] . "'");
        if(!$result->fetchArray() >= 1){
            $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Le joueur cible n'est pas ban !");
        }else{
            $maria->query("DELETE FROM bans WHERE `username` = '".$args[0]."'");
        }

        $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien unban le joueur §1{$args[0]}");
    }

}