<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\PlayerForm;

class BoutiqueCommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("boutique", "§o§6Solaria §7» §8Accedez a la boutique", "/boutique", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof PlayerManager){
            $PlayerForm = new PlayerForm();
            $PlayerForm->boutique($sender);
        }
    }

}