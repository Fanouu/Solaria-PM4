<?php

namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\PlayerForm;

class ParticuleCommand extends Command{
    use SolariaCommand;

    public function __construct() {
        parent::__construct("particle", "§o§6Solaria §7» §8Accedez au particule", "/particcle", ["particule"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof PlayerManager){
            $PlayerForm = new PlayerForm();
            $PlayerForm->particule($sender);
        }
    }

}