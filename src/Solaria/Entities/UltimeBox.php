<?php

namespace Solaria\Entities;

use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\item\Item;
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

class UltimeBox extends Human{

    public static function getNetworkTypeId() : string{ return EntityIds::NPC; }

    protected function getInitialSizeInfo() : EntitySizeInfo{ return new EntitySizeInfo(1.8, 0.6, 1.62); }

    public function __construct(Location $location, ?CompoundTag $nbt = null) {
        $this->setImmobile(true);
        $this->setNameTagAlwaysVisible(true);
        $this->setNameTag("§f===============\n§l§7>>§r Caisse §dUltime §l§7<<\n§r§f===============");

        $path = Core::getInstance()->getDataFolder() . "textures/ultime_box2.png";
        $data = Utils::PNGtoBYTES($path);
        $cape = "";
        $path = Core::getInstance()->getDataFolder() . "models/so_box.json";
        $geometry = file_get_contents($path);

        $skin = new Skin($this->getName(), $data, $cape, "geometry.unknown", $geometry);
        $this->setSkin($skin);
        parent::__construct($location, $skin, $nbt);
    }

    public function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
    }

    public function attack(EntityDamageEvent $source): void {
        if($source instanceof EntityDamageByEntityEvent){
            $item = $source->getDamager()->getInventory()->getItemInHand();
            if($item->getId() === 511){
                $this->flagForDespawn();
            }else{
                if($item->getId() === 339){
                    $nametag = $item->getNamedTag();
                    if(!is_null($nametag->getTag("ticketType")) && $nametag->getString("ticketType") === "ultime"){
                        $it = ItemFactory::getInstance()->get($item->getId(), 0, 1);
                        $source->getDamager()->getInventory()->removeItem($it);
                        $source->getDamager()->getServer()->broadcastMessage("Le joueur §e{$source->getDamager()->getName()} §fvient d'ouvrire une §6Caisse Ultime §f!");

                        $rdm = mt_rand(0, 100);

                        if($rdm <= 2){
                            $newItem = ItemFactory::getInstance()->get(748, 0, 1);
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 10){
                            $newItem = ItemFactory::getInstance()->get(ItemIds::CHAINMAIL_CHESTPLATE, 0, 1);
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 18){
                            $newItem = ItemFactory::getInstance()->get(ItemIds::CHAINMAIL_LEGGINGS, 0, 1);
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 20){
                            $newItem = ItemFactory::getInstance()->get(751, 0, 1);
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 24){
                            $newItem = ItemFactory::getInstance()->get(743, 0, 1);
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 29){
                            $newItem = ItemFactory::getInstance()->get(742, 0, 1);
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 30){
                            $newItem = ItemFactory::getInstance()->get(742, 0, 4);
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 45){
                            $newItem = ItemFactory::getInstance()->get(ItemIds::SLIME_BALL, 0, 4);
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 60){
                            $newItem = ItemFactory::getInstance()->get(ItemIds::COOKIE, 0, 8);
                            $source->getDamager()->getInventory()->addItem($newItem);
                            return;
                        }

                        if($rdm <= 70){
                            $key = ItemFactory::getInstance()->get(ItemIds::RAW_MUTTON, 0, 16);
                            $source->getDamager()->getInventory()->addItem($key);
                            return;
                        }

                        if($rdm <= 90){
                            $key = ItemFactory::getInstance()->get(ItemIds::GOLDEN_APPLE, 0, 16);
                            $source->getDamager()->getInventory()->addItem($key);
                            return;
                        }

                        if($rdm <= 100){
                            $newItem = ItemFactory::getInstance()->get(ItemIds::BOW, 0, 1);
                            $newItem->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PUNCH(), 2));
                            $source->getDamager()->getInventory()->addItem($newItem);

                            $newItem = ItemFactory::getInstance()->get(ItemIds::ARROW, 0, 1);
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
        $form->setTitle("§f- §6Box Ultime §f-");
        $form->setContent("Voici les différent §citem§f obtensible dans la box: \n §f - §eCasque en Platine §o(P4U3)§r §8(x1) §f-§6 2%%\n §f - §ePlastron en Jade §o(P4U3)§r §8(x1) §f-§6 8%%\n §f - §eJambière en Jade §o(P4U3)§r §8(x1) §f-§6 8%%\n §f - §eBottes en Platine §o(P4)§r §8(x1) §f-§6 2%%\n §f - §eEpée en Platine §o(T5U3)§r §8(x1) §f-§6 4%%\n §f - §ePlatine §8(x1) §f-§6 5%%\n §f - §ePlatine §8(x4) §f-§6 1%%\n §f - §eStick Of God §8(x4) §f-§6 15%%\n §f - §ePomme Saphir §8(x8) §f-§6 15%%\n §f - §eBurger §8(x16) §f-§6 10%%\n §f - §ePomme en Or §8(x16) §f-§6 20%%\n §f - §eArc Punch §8(x4) §f-§6 10%%");
        $form->addButton("§cFermer");
        $player->sendForm($form);
    }

    public static function createSimpleForm(callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }

    public function getName(): string {
        return "RareBox";
    }
}