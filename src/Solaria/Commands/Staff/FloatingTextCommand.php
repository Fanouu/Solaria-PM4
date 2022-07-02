<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Entities\FloatingText;

class FloatingTextCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("floatingtext", "§o§6Solaria §7» §8Faire spawn un text flotant", "/floatingtext <nom>", []);
        $this->setPermission("staff.floatingtext");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("staff.floatingtext")) return $this->errorManager()->noPerms($sender);
            if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/kick <nom flotant>");
            
            if($args[0] !== "delete"){
                $entity = new FloatingText($sender->getLocation());
                $entity->setNameTag(str_replace("{n}", "\n", $args[0]));
                $entity->spawnToAll();
            
                $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien fait spawn un floating text !");
            }else{
                foreach($sender->getServer()->getWorldManager()->getWorlds() as $level){
                    foreach($level->getEntities() as $entities){
                        if($entities instanceof FloatingText){
                            $entities->flagForDespawn();
                        }
                    }
                }
            }
        }
    }

}