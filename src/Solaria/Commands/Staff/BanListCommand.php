<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\ModsForm;

class BanListCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("banlist", "§o§6Solaria §7» §8Voir la liste des joueur banni", "/banlist", []);
        $this->setPermission("staff.ban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        
        if(!$sender->hasPermission("staff.ban")) return $this->errorManager()->noPerms($sender);

        $result = $this->database()->query("SELECT * FROM bans");
        $bans = [];
        $data = [];
        while($res = $result->fetchArray()){
            array_push($data, $res);
        }
        
        foreach($data as $fetch => $value){
            $bans[] = " §1" . $value["username"] . " §7banni par §9" . $value["staff"] . " §7pour: §9" . $value["reason"];
        }
        $count = count($data);
        $sender->sendMessage("Liste des utilisateur banni [$count]\n§7-" . implode("\n§7-", $bans));
    }

}