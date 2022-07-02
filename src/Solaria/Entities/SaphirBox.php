<?php
    
namespace Solaria\Entities;

use pocketmine\entity\Human;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Skin;

use Solaria\Core;
use Solaria\Utils\Utils;
use Solaria\Managers\PlayerManager;
use Solaria\Forms\FormAPI\SimpleForm;

use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;

class SaphirBox extends Human{
    
    public static function getNetworkTypeId() : string{ return EntityIds::NPC; }
    
    protected function getInitialSizeInfo() : EntitySizeInfo{ return new EntitySizeInfo(1.8, 0.6, 1.62); }

    public function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
        $this->setImmobile(true);
        $this->setNameTagAlwaysVisible(true);
        $this->setNameTag("§f===============\n§l§7>>§r Coffre en §1Saphir §l§7<<\n§r§f===============");
        
        $path = Core::getInstance()->getDataFolder() . "textures/saphir_box.png";
        $data = Utils::PNGtoBYTES($path);
        $cape = "";
        $path = Core::getInstance()->getDataFolder() . "models/saphir.geo.json";
        $geometry = file_get_contents($path);

        $skin = new Skin($this->getName(), $data, $cape, "geometry.box", $geometry);
        $this->setSkin($skin);
    }
    
   public function attack(EntityDamageEvent $source): void {
       if($source instanceof EntityDamageByEntityEvent){
           $item = $source->getDamager()->getInventory()->getItemInHand();
           if($item->getId() === 511){
               $this->flagForDespawn();
            }else{
               if($item->getId() === 339){
                   $nametag = $item->getNamedTag();
                   if(!is_null($nametag->getTag("ticketType")) && $nametag->getString("ticketType") === "saphir"){
                       $it = ItemFactory::getInstance()->get($item->getId(), 0, 1);
                       $source->getDamager()->getInventory()->removeItem($it);
                       $source->getDamager()->getServer()->broadcastMessage("Le joueur §e{$source->getDamager()->getName()} §fvient d'ouvrire un §6Coffre en saphir §f!");
                       
                       $rdm = mt_rand(0, 100);
                       
                       if($rdm <= 15){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_HELMET, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 30){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_CHESTPLATE, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 45){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_LEGGINGS, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 60){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_BOOTS, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 70){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_SWORD, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 74){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::BOW, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PUNCH(), 2));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           
                           $newItem = ItemFactory::getInstance()->get(ItemIds::ARROW, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 82){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT, 0, 16);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 92){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::BLAZE_POWDER, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 96){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::CHAIN_BOOTS, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 98){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_AXE, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 99){
                           $newItem = ItemFactory::getInstance()->get(341, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 100){
                           $newItem = ItemFactory::getInstance()->get(464, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                   }else{
                       $this->ui($source->getDamager());
                   }
               }else{
                   $this->ui($source->getDamager());
               }
               $source->cancel();
            }
       }
       $source->cancel();
    }
    
    public function ui(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
            }
            return true;

        });
        $form->setTitle("§f- §6Coffre en Saphir §f-");
        $form->setContent("Voici les différent §citem§f obtensible dans la box: \n §f - §eCasque en Saphir §o(P4)§r §8(x1) §f-§6 15%%\n §f - §EPlastron en Saphir §o(P4)§r §8(x1) §f-§6 15%%\n §f - §eJambière en Saphir §o(P4)§r §8(x1) §f-§6 15%%\n §f - §eBottes en Saphir §o(P4)§r §8(x1) §f-§6 15%%\n §f - §eEpée en Saphir §o(T5)§r §8(x1) §f-§- 10%%\n §f - §eBaton de Speed §8(x1) §f-§6 10%%\n §f - §eLingot de Saphir §8(x16) §f-§6 8%%\n §f - §eArc Punch §8(x1) §f-§6 4%%\n §f - §eBottes en Platine §8(x1) §f-§6 4%%\n §f - §eEpée en Platine §8(x1) §f-§6 2%%\n §f - §eStick Of God §8(x1) §f-§6 1%%\n §f - §eLingot de Jade §8(x1) §f-§6 1%%");
        $form->addButton("§cFermer");
        $player->sendForm($form);
    }
    
    public static function createSimpleForm(callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }
    
    public function getName(): string {
        return "SaphirBox";
    }
}