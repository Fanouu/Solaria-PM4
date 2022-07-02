<?php

namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Solaria\Commands\SolariaCommand;

class RedemCommand extends Command {

    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("redem", "§o§6Solaria §7» §8REDEM", "/redem", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if(!Server::getInstance()->isOp($sender->getName())) return $this->errorManager()->noPerms($sender);

        Server::getInstance()->forceShutdown();

    }
}