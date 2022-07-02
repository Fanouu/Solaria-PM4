<?php
    
namespace Solaria\Events\Player;

use pocketmine\event\player\PlayerPreLoginEvent;
use Solaria\Core;
use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

use Solaria\Managers\MessageManager;
use Solaria\Forms\Form\PlayerForm;
use Solaria\Utils\Provider;
use Solaria\Utils\Utils;

class PlayerJoin implements Listener{
    use PlayerListener;

    public function PlayerPreLogin(PlayerPreLoginEvent $event){
        $pname = $event->getPlayerInfo()->getUsername();
        $result = Provider::database()->query("SELECT * FROM player WHERE `username` = '" . $pname . "'");
        if(!$result->fetchArray() >= 1) {

            Core::getInstance()->getLogger()->info("Player with name $pname is not found");
            Core::getInstance()->getLogger()->info("Player creation for $pname");
            Provider::database()->query("INSERT INTO player (`username` , `uuid`, `IP`,`money`, `rank`, `pointboutique`) VALUES ('" . $pname . "','" . (string) $event->getPlayerInfo()->getUuid() . "', '" . (string) $event->getIp() . "', '250', 'player', '0')");
            Provider::database()->query("INSERT INTO cooldown (`username`, `kit`) VALUES ('" . $pname . "','0000|0000|0000|0000|0000')");
            Core::getInstance()->getLogger()->info("Player create succes for $pname");
        }
    }
    
    public function PlayerJoinEvent(PlayerJoinEvent $event){
        
        $event->setJoinMessage("");
        $player = $event->getPlayer();

        $player->sendScoreboard();
        
        Server::getInstance()->broadcastMessage($this->messageManager()->getMessage($player, "player_join", true));
        if(!$player->hasPlayedBefore()){
            Server::getInstance()->broadcastMessage($this->messageManager()->getMessage($player, "first_join", true));
            $form = new PlayerForm();
            $form->firstJoin($player);
        }

        Utils::loadCapes($player);
        
        $player->sendMessage($this->messageManager()->getMessage($player, "private_msg_join"));
        
        $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSpawnLocation(), 0, 0);
    }
}