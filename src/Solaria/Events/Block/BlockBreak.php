<?php
    
namespace Solaria\Events\Block;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use Solaria\Events\Player\PlayerListener;
use Solaria\Managers\PlayerManager;

use Solaria\Core;
use Solaria\Tasks\RandomOrTask;
use pocketmine\block\BlockFactory;
use pocketmine\math\Vector3;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemFactory;

class BlockBreak implements Listener{
    use PlayerListener;
    
    
    public function onBreak(BlockBreakEvent $event){
        $block = $event->getBlock();
        $player = $event->getPlayer();
        
        if($block->getId() === 153){
            $rdm = mt_rand(0, 100);
            $drops = [];
            if($rdm <= 50){
                $drops = [ItemFactory::getInstance()->get(ItemIds::DIAMOND, 0, 5)];
            }
            
            if($rdm <= 75 && $rdm > 50){
                $drops = [ItemFactory::getInstance()->get(ItemIds::REDSTONE, 0, 10)];
            }
            
            if($rdm <= 95 && $rdm > 75){
                $drops = [ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT, 0, 1)];
            }
            
            if($rdm <= 100 && $rdm > 95){
                $drops = [ItemFactory::getInstance()->get(ItemIds::EMERALD, 0, 1)];
            }
            $event->setDrops($drops);
        }

        if($block->getId() === BlockLegacyIds::GOLD_ORE){
            $event->setDrops([ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT, 0, 1)]);
        }
    }
}