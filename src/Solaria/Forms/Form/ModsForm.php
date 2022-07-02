<?php
    
namespace Solaria\Forms\Form;

use Solaria\Forms\FormAPI\SimpleForm;
use Solaria\Forms\FormAPI\CustomForm;
use Solaria\Managers\PlayerManager;
use Solaria\Utils\Provider;
use Solaria\Utils\Utils;

use pocketmine\Server;

class ModsForm{
    
   private static $OnlinePlayers;
    
    public function ban(PlayerManager $player){
        self::$OnlinePlayers = [];
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            
            $this->banToPlayer($player, self::$OnlinePlayers[$data]["username"], self::$OnlinePlayers[$data]);
            
            return true;

        });
        $form->setTitle("§f- §6Ban §f-");
        $form->setContent("§e» §fUn cheat ou autre hésiter pas à ban !");
        $i = 0;
        foreach(Server::getInstance()->getOnlinePlayers() as $player){
            $form->addButton($player->getName());
            self::$OnlinePlayers[$i] = [
                "username" => $player->getName(),
                "uuid" => $player->getUniqueId(),
                "xuid" => $player->getPlayerInfo()->getXuid(),
                "IP" => $player->getNetworkSession()->getIp()
            ];
            $i++;
        }
        $player->sendForm($form);
    }
    
    public function banToPlayer(PlayerManager $player, $targetname, array $targetInfo){
        $form = self::createCustomForm(function (PlayerManager $player, array $data = null) use ($targetInfo){
            $result = $data;
            if($result === null){
                return true;
            }
            
            
            $player->sendMessage("min:" . $data[1] . " heures:" . $data[2] . " day:" . $data[3] . " reason:" . $data[4] . "");
            foreach($targetInfo as $index => $value){
                $player->sendMessage("$index => $value");
            }
            $min = time() + (int)$data[1]*60;
            $hours = $min + (int)$data[2]*60*60;
            $time = $hours + (int)$data[3]*24*60*60;
            $maria = Provider::database();
            $maria->query("INSERT INTO bans (`username` , `uuid`, `IP`,`xuid`, `time`, `reason`, `staff`) VALUES ('". $targetInfo["username"] ."', '". $targetInfo["uuid"] ."', '". $targetInfo["IP"] ."', '". $targetInfo["xuid"] ."', '". $time ."', '". $data[4] ."', '". $player->getName() ."')");
            $maria->close();
            
            $times = Utils::convertTime($time);
            $toBan = $player->getServer()->getPlayerExact($targetInfo["username"]);
            if($toBan) {
                $toBan->kick("§6» §fVous avez été banni du server pour §e". $data[4] ."\n§e» §fpendant §7" . $times["day"] . "§fd §7" . $times["hours"] . "§fh §7" . $times["minuts"] . "§fm\n§e» §fPar: §7".$player->getName()."\n§o§cSi ceci est-une erreur merci de venir sur notre support discord");
            }
            Server::getInstance()->broadcastMessage("§o§f[§6§l!!!§r§o]§r§f joueur §e{$targetInfo["username"]} §fa été banni du server par §6{$player->getName()} §f!");
            return true;
        });
        $form->setTitle("§f- §6Ban §f-");
        $form->addLabel("Procès de §7$targetname\n§r§oA noté l'utilisateur se vera automatiquement ban IP, Unique ID, Xbox Live Unique ID");
        $form->addSlider("Combien de minute:", 1, 60);
        $form->addSlider("Combien d'heures:", 0, 60);
        $form->addSlider("Combien de jours:", 0, 365);
        $form->addInput("Quel est la raison?", "ex: Cheat, Menace, Personne dangereuse");
        $form->sendToPlayer($player);
    }
    
    
    public static function createSimpleForm(callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }

    public static function createCustomForm(callable $function = null) : CustomForm {
        return new CustomForm($function);
    }
    
}