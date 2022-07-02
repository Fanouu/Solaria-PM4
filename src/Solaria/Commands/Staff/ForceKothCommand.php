<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\ModsForm;
use Solaria\Managers\KothManager;

class ForceKothCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("forcekoth", "» §1Solaria §7ForceKoth", "/forcekoth", []);
        $this->setPermission("staff.forcekoth");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("staff.forcekoth")) return $this->errorManager()->noPerms($sender);
            
            KothManager::getInstance()->start();
        }
    }

}