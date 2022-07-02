<?php

namespace Solaria\Tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\lang\Language;

use Solaria\Core;

class VoteAsync extends AsyncTask{

    private $pname;
    private $solaria_vote_key = "wcjGmHEuomp610NoNwZbQ4vPSHoSuacEYLo";

    public function __construct(string $pname){
        $this->pname = $pname;
    }

    public function onRun(): void{
        $result = Internet::getURL("https://minecraftpocket-servers.com/api/?object=votes&element=claim&key=" . $this->solaria_vote_key . "&username=" . str_replace(" ", "%20", $this->pname));
        if($result === "1") var_dump(01); //Internet::getURL("https://minecraftpocket-servers.com/api/?action=post&object=votes&element=claim&key={$this->solaria_vote_key}&username=" . str_replace(" ", "%20", $this->pname));
        $this->setResult($result);

    }

    public function onCompletion(): void{

        $result = $this->getResult();
        $player = Core::getInstance()->getServer()->getPlayerExact($this->pname);
        
        if(!$player) return;

        switch($result){
            case "0":
            $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas voté aujourd'hui ! §oPour voté -> §ehttps://minecraftpocket-servers.com/server/111615/vote/");
            break;
            
            case "1":
                $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "givekey vote 1 " . $this->pname . "");
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien réçus votre récompense de vote !");
                
                Server::getInstance()->broadcastMessage("§o§f[§6§lVote§r§o]§r§f §r§fMerci à §9{$this->pname} §fqui vient de voté pour le server ! Toi aussi envie de recevoir une récompense journalière ? Vote désormais pour le §1serveur§f sur §8-> §7https://minecraftpocket-servers.com/server/111615/vote/");
            break;
                
            default:
                 $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez déjâ voté aujourd'hui !");
        }
        
    }
}