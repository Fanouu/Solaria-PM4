<?php
    
namespace Solaria\Commands\Joueur;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectInstance;

class NightVisionCommand extends Command{
    use SolariaCommand;
    
    public function __construct() {
        parent::__construct("nightvision", "§o§6Solaria §7» §8Activer/Désactiver la vision nocturne", "/nightvision", ["nv"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            if($sender->getEffects()->has(VanillaEffects::NIGHT_VISION())) return $sender->getEffects()->remove(VanillaEffects::NIGHT_VISION());
            
            $sender->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 5*60*60*20, 0, false));
        }
        
    }

}