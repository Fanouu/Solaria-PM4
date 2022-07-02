<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\PlayerForm;

class MyMoneyCommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("mymoney", "§o§6Solaria §7» §8Votre argent", "/mymoney", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof PlayerManager){
            $sender->sendMessage("§7Vous possedez: §e{$sender->myMoney()} ");
        }
    }

}