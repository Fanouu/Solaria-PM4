<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\Server;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\data\bedrock\EnchantmentIdMap;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\VanillaItems;
use pocketmine\entity\effect\EffectInstance;

use Solaria\Managers\MessageManager;
use Solaria\Utils\Utils;

class PlayerItemConsume implements Listener{
    use PlayerListener;
    
    private static $coold = [];
    private static $mcoold = [];
    
    public function PlayerItemConsumeEvent(PlayerItemConsumeEvent $event){
        
        $item = $event->getItem();
        $player = $event->getPlayer();
        $pname = $event->getPlayer()->getName();
        
        if($item->getId() === 373 && $item->getMeta() === 4){
            
        }
        
        if($item->getId() === 322){
            if(!isset(self::$coold[$pname]) || self::$coold[$pname] - time() <= 0){     
                $event->uncancel();
                self::$coold[$pname] = time() + 10;
            }else{
                $time = Utils::convertTime(self::$coold[$pname]);
                if($time["minuts"] > 0){
                    $timeRestant = "§e{$time["minuts"]} minute(s)";
                }else{
                    $timeRestant = "§e{$time["seconds"]} seconde(s)";
                }
                
                $event->cancel();
                $player->sendPopup("§6»§f Merci de patienter $timeRestant §f!");
            }
        }
    }
}