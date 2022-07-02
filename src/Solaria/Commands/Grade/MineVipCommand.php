<?php

namespace Solaria\Commands\Grade;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

use pocketmine\world\Position;

class MineVipCommand extends Command{
    use SolariaCommand;

    public function __construct() {
        parent::__construct("minagevip", "§o§6Solaria §7» §8Ce teleporter au Minage VIP", "/minevip", []);
        $this->setPermission("grade.minevip");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("grade.minevip")) return $this->errorManager()->noPerms($sender);
            $sender->teleport(new Position(51, 208, 26, $sender->getServer()->getWorldManager()->getWorldByName("Mine")));
            $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien été teleporté au §1minage§f !");
        }

    }

}