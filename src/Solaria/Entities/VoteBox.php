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

class VoteBox extends Human{
    
    public static function getNetworkTypeId() : string{ return EntityIds::NPC; }
    
    protected function getInitialSizeInfo() : EntitySizeInfo{ return new EntitySizeInfo(1.8, 0.6, 1.62); }

    public function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
        $this->setImmobile(true);
        $this->setNameTagAlwaysVisible(true);
        $this->setNameTag("§f===============\n§l§7>>§r Caisse de §8Vote §l§7<<\n§r§f===============");
        
        $path = Core::getInstance()->getDataFolder() . "textures/vote_box.png";
        $data = Utils::PNGtoBYTES($path);
        $cape = "";
        $path = Core::getInstance()->getDataFolder() . "models/box.geo.json";
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
                   if(!is_null($nametag->getTag("ticketType")) && $nametag->getString("ticketType") === "vote"){
                       
                       if(!$source->getDamager()->getInventory()->canAddItem(ItemFactory::getInstance()->get(0, 0))){
                           $source->getDamager()->sendActionBarMessage("§cVous n’avez pas asser de place dans votre inventaire !");
                           return;
                       }
                       $it = ItemFactory::getInstance()->get($item->getId(), 0, 1);
                       $source->getDamager()->getInventory()->removeItem($it);
                       $source->getDamager()->getServer()->broadcastMessage("Le joueur §e{$source->getDamager()->getName()} §fvient d'ouvrire une §6caisse de vote §f!");
                       
                       $rdm = mt_rand(0, 103.01);
                       
                       if($rdm <= 20){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::DIAMOND_HELMET, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 40){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::DIAMOND_CHESTPLATE, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 60){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::DIAMOND_LEGGINGS, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 80){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::DIAMOND_BOOTS, 0, 1);
                           $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 87){
                           $newItem = ItemFactory::getInstance()->get(344, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 92){
                           $newItem = ItemFactory::getInstance()->get(369, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                        if($rdm <= 102){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_SWORD, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 103){
                           $newItem = ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT, 0, 1);
                           $source->getDamager()->getInventory()->addItem($newItem);
                           return;
                       }
                       
                       if($rdm <= 103.01){
                           $newItem = ItemFactory::getInstance()->get(743, 0, 1);
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
        $form->setTitle("§f- §6Caise de Vote §f-");
        $form->setContent("Voici les différent §citem§f obtensible dans la box: \n §f - §ECasque en Diamant §o(P4)§r §8(x1) §f-§6 20%%\n §f - §ePlastron en Diamant §o(P4)§r §8(x1) §f-§6 20%%\n §f - §eJambière en Diamant §o(P4)§r §8(x1) §f-§6 20%%\n §f - §eBottes en Diamant §o(P4)§r §8(x1) §f-§6 20%%\n §f - §eEpée en Saphir §8(x1) §f-§6 20%%\n §f - §eFake Pearl §8(x1) §f-§6 7%%\n §f - §eStick de force §8(x1) §f-§6 5%%\n §f - §eMinerai de Saphir §8(x1) §f-§6 1%%\n §f - §eépée platine §8(x1) §f-§6 0.01%%");
        $form->addButton("§cFermer");
        $player->sendForm($form);
    }
    
    public static function createSimpleForm(callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }
    public function getName(): string {
        return "VoteBox";
    }
}