<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use pocketmine\console\ConsoleCommandSender;

class TopMoneyCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("topmoney", "§o§6Solaria §7» §8Classement des joueurs avec le plus d'argent", "/topmoney", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        
        $maria = $this->database();
        $result = $maria->query("SELECT * FROM player");
        $allMoney = [];
        $data = [];
        while($res = $result->fetchArray(SQLITE3_ASSOC)){
            array_push($data, $res);
        }
        
        foreach($data as $index => $value){
            $allMoney[$value["username"]] = $value["money"];
        }
        
        arsort($allMoney);
        $i = 1;
        $msg = [];
        $mec = null;
        foreach($allMoney as $pname => $money){
            if($i === 11 && !$mec === null) break;
            if($sender instanceof ConsoleCommandSender && $i === 11) break;
            $msg[] = "$i# §e- §6$pname §favec $money  §f- ";
            if($pname === $sender->getName()){ $mec = $i; }
            $i++;
        }
        $me = "";
        if($sender instanceof PlayerManager){
            $me = "Vous êtes classé §e$mec# §favec §6" .$allMoney[$sender->getName()] . "";
        }
        
        $sender->sendMessage("Solaria §6Top §Money§f: \n" . implode("\n", $msg) . "\n$me");
    }

}