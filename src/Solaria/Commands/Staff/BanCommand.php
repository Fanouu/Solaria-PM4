<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\ModsForm;

class BanCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("ban", "§o§6Solaria §7» §8Bannir un joueur du server", "/ban [player]", []);
        $this->setPermission("staff.ban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("staff.ban")) return $this->errorManager()->noPerms($sender);
            
            $form = new ModsForm();
            if(!isset($args[0]) || $args[0] === "" || $args[0] === " "){ $form->ban($sender); }
            if(isset($args[0])){ $form->banToPlayer($sender, $args[0], [
                "username" => $args[0],
                "uuid" => "null",
                "xuid" => "null",
                "IP" => "null"
            ]); }
        }
    }

}