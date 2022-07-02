<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;
use pocketmine\item\ItemFactory;

use Solaria\Managers\MessageManager;
use Solaria\Forms\Form\PlayerForm;

class PlayerInteract implements Listener{
    use PlayerListener;
    
    private static $coold = [];
    
    public function PlayerInteractEvent(PlayerInteractEvent $event){
        
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();
        
        if($block->getId() === 145){
            $event->cancel();
            $form = new PlayerForm();
            $form->enclume($player);
        }
        
        if($block->getId() === 116){
            $event->cancel();
            $form = new PlayerForm();
            $form->enchant($player);
        }
        
        if($item->getId() === 288){
            if(!isset(self::$coold[$player->getName()]) || self::$coold[$player->getName()] - time() <= 0){
                $motions = clone $player->getMotion();     
                //$motions->x += $player->getDirectionVector()->getX();
                $motions->y += $player->getEyeHeight() * 1.2;
                //$motions->z += $player->getDirectionVector()->getZ() * 1;
            
                $player->setMotion($motions);
                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(288, 0, 1));
                self::$coold[$player->getName()] = time() + 2;
            }
        }
        
        if($event->getBlock()->getId() === 117){
            $event->cancel();
            $form = new PlayerForm();
            $form->alambic($player);
        }
    }
}