<?php

namespace Solaria\Tasks;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use Solaria\Core;

class AutoBroadcasterTask extends Task{

    public static $counter = 0;
    public static $time = 0;
    public static $messages = ["§o§f[§6§l!!!§r§o]§r§f §fEnvie d'avoir un §6Grade / Particules / Clés §f? On accepte désormais les §aPaySafeCard §f et aussi les payements via §ePayPal §f Pour tous les achats sont disponibles sur le discord de §6Solaria §f!", "§o§f[§6§l???§r§o]§r§f Envie d'obtenir des récompense Gratuitement Ainsi que des §6Keys §f? Votez pour le serveur afin de les obtenir -> §6https://minecraftpocket-servers.com/server/111615/", "§o§f[§6§l!!!§r§o]§r§f §fVous pouvez désormais recevoir des §aJetons §fjuste en purifiant vos lingots De §9Saphir §f au §6/purif §f!", "Bon jeux sur §6Solaria §eV6"];

    public function onRun(): void{

        if(self::$time === 900){
            $serv = Core::getInstance()->getServer();

            $serv->broadcastMessage(self::$messages[self::$counter]);

            self::$counter++;
            if(self::$counter == count(self::$messages)){
                self::$counter = 0;
            }
            self::$time = 0;

        }
        self::$time++;
    }

}