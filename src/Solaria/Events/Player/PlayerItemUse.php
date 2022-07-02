<?php
    
namespace Solaria\Events\Player;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\item\Item;
use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\Server;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\data\bedrock\EnchantmentIdMap;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\VanillaItems;
use pocketmine\entity\effect\EffectInstance;

use Solaria\Managers\MessageManager;
use Solaria\Utils\Utils;

class PlayerItemUse implements Listener{
    use PlayerListener;
    
    private static $coold = [];
    private static $sogStick = [];
    private static $strStick = [];
    private static $speStick = [];
    private static $mcoold = [];
    private static $jcoold = [];
    
    public function PlayerItemUseEvent(PlayerItemUseEvent $event){
        
        $item = $event->getItem();
        $player = $event->getPlayer();
        $pname = $event->getPlayer()->getName();

        if($item->getId() === 340){
            $item->pop();
            Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), "f addpower {$player->getName()} 100");
        }
        
        if($item->getId() === 288){
            $motions = clone $player->getMotion();     
            $motions->y += $player->getEyeHeight() * 1.2;
            
            $player->setMotion($motions);
            $player->getInventory()->removeItem(ItemFactory::getInstance()->get(288, 0, 1));
        }
        
        if($item->getId() === 438 && $item->getMeta() === 22){
            $namedtag = $item->getNamedTag();
            if(!is_null($namedtag->getTag("Stackable_Potion:solaria"))){
                $count = (int)$namedtag->getString("Stackable_Potion:solaria");
                if($count == 2){
                    $potion = VanillaItems::STRONG_HEALING_SPLASH_POTION();
                    $potion->getNamedTag()->setString("Stackable_Potion:solaria", "1");
                    $potion->setCustomName("§r§7Potion de §o§dheal II §r§7(x1)");
                    $player->getInventory()->setItemInHand($potion);
                }
            }
        }
        
        if($item->getId() === 261){
            if($item->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(20))){
                $event->cancel();
                if(!isset(self::$coold[$pname]) || self::$coold[$pname] - time() <= 0){
                    self::$coold[$pname] = time() + 5;
                    $motions = clone $player->getMotion();
                
                    $motions->x += $player->getDirectionVector()->getX() *  3.2;
                    $motions->y += $player->getDirectionVector()->getY() * 1.5;
                    $motions->z += $player->getDirectionVector()->getZ() * 3.2;
                            
                    $player->setMotion($motions);
                    $item->applyDamage(1);
                
                }else{
                     $player->sendPopup(str_replace("{time}", self::$coold[$pname] - time(), $this->messageManager()->getMessage($player, "bowpunch_coold")));
                }
            }
        }
        if($item->getId() === 341){
            if(!isset(self::$sogStick[$pname]) || self::$sogStick[$pname] - time() <= 0){
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 5*20, 3));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 5*20, 2));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::INVISIBILITY(), 5*20, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 5*20, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 5*20, 1));
                
                self::$sogStick[$pname] = time() + 5*60;                         
            }else{
                $time = Utils::convertTime(self::$sogStick[$pname]);
                if($time["minuts"] > 0){
                    $timeRestant = "§e{$time["minuts"]} minute(s)";
                }else{
                    $timeRestant = "§e{$time["seconds"]} seconde(s)";
                }
                
                $player->sendPopup("§6»§f Merci de patienter $timeRestant §f!");
            }
        }
        
        if($item->getId() === 369){
            if(!isset(self::$strStick[$pname]) || self::$strStick[$pname] - time() <= 0){
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 5*20, 1)); 
                self::$strStick[$pname] = time() + 5*60;                         
            }else{
                $time = Utils::convertTime(self::$strStick[$pname]);
                if($time["minuts"] > 0){
                    $timeRestant = "§e{$time["minuts"]} minute(s)";
                }else{
                    $timeRestant = "§e{$time["seconds"]} seconde(s)";
                }
                
                $player->sendPopup("§6»§f Merci de patienter $timeRestant §f!");
            }
        }
        
        if($item->getId() === 377){
            if(!isset(self::$speStick[$pname]) || self::$speStick[$pname] - time() <= 0){
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 5*20, 1));
                
                self::$speStick[$pname] = time() + 5*60;                         
            }else{
                $time = Utils::convertTime(self::$speStick[$pname]);
                if($time["minuts"] > 0){
                    $timeRestant = "§e{$time["minuts"]} minute(s)";
                }else{
                    $timeRestant = "§e{$time["seconds"]} seconde(s)";
                }
                
                $player->sendPopup("§6»§f Merci de patienter $timeRestant §f!");
            }
        }
        
        if($item->getId() === 423){
            if(!isset(self::$jcoold[$pname]) || self::$jcoold[$pname] - time() <= 0){
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 5*50*20, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 5*50*20, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 10*20, 0));
                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(423, 0, 1));
                self::$jcoold[$pname] = time() + 1*60;                         
            }else{
                $time = Utils::convertTime(self::$jcoold[$pname]);
                if($time["minuts"] > 0){
                    $timeRestant = "§e{$time["minuts"]} minute(s)";
                }else{
                    $timeRestant = "§e{$time["seconds"]} seconde(s)";
                }
                
                $player->sendPopup("§6»§f Merci de patienter $timeRestant §f!");
            }
        }
        
        if($item->getId() === ItemIds::COOKIE){
            if(!isset(self::$mcoold[$pname]) || self::$mcoold[$pname] - time() <= 0){
                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 30*20, 4));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), 30*20, 3));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 5*60*20, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 5*60*20, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 5*60*20, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 5*60*20, 1));
                $event->cancel();
                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(ItemIds::COOKIE, 0, 1));
                self::$mcoold[$pname] = time() + 10*60;
            }else{
                $time = Utils::convertTime(self::$mcoold[$pname]);
                if($time["minuts"] > 0){
                    $timeRestant = "§e{$time["minuts"]} minute(s)";
                }else{
                    $timeRestant = "§e{$time["seconds"]} seconde(s)";
                }
                $player->sendPopup("§6»§f Merci de patienter $timeRestant §f!");
            }
        }
    }
}