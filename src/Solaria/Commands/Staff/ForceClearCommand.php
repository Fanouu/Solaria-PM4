<?php

namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\ExperienceManager;
use pocketmine\entity\object\ItemEntity;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\ModsForm;
use Solaria\Managers\KothManager;

class ForceClearCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("forceclear", "§o§6Solaria §7» §8Forcer le clearlagg", "/forceclear", []);
        $this->setPermission("staff.forceclear");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("staff.forceclear")) return;

            $count = 0;
            foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world){
                foreach ($world->getEntities() as $entity){
                    if($entity instanceof ItemEntity || $entity instanceof ExperienceManager){
                        $entity->flagForDespawn();
                        $entity->close();
                        $count++;
                    }
                }
            }

            Server::getInstance()->broadcastTip("§o§f[§6§l!!!§r§o]§r§f §e$count entity§f clear");
        }
    }

}