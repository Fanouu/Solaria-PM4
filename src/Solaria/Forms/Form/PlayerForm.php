<?php
    
namespace Solaria\Forms\Form;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use Solaria\Forms\FormAPI\SimpleForm;
use Solaria\Forms\FormAPI\CustomForm;
use Solaria\Managers\PlayerManager;
use Solaria\Core;
use Solaria\Managers\KothManager;

use Solaria\Managers\WebHookManager;
use Solaria\Utils\Provider;
use Solaria\Utils\Utils;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\VanillaItems;
use pocketmine\item\Armor;
use pocketmine\item\Tool;
use pocketmine\lang\Language;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\entity\Skin;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\entity\Location;

class PlayerForm{
    
    private $itemPrice;
    private $itemItem;
    private $itemType;
    private $itemName;
    
    public function firstJoin(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            return true;

        });
        $form->setTitle("welcome");
        $form->setContent("Bienvenue sur la §eV6 §fdu §bkitmap §fde §6Solaria §f\nPleins de nouveauté arrivent prochainement ! Le server est actuellement en §abêta §fpendant §e1 semaine §rce qui explique que pas mal de chose étant présent aux version ultérieure ne le sont pas actuellement, pas d'inquiétude tout reviendra d'ici 1 semaine. Merci de votre compréhension.\nCordialement §cFanxyz§f, développeur et administrateur de §6Solaria Kitmap");
        $form->addButton("§e§l» §r§6Commencer à Jouer");
        $player->sendForm($form);
    }

    public function atout(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:
                    if(!$player->hasPermission("atout.force")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return;
                    }

                    if($player->getEffects()->has(VanillaEffects::STRENGTH())){
                        $player->getEffects()->remove(VanillaEffects::STRENGTH());
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien retirer l'atout §6force §f!");
                        return;
                    }

                    $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 5*60*60*20, 0, false));
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien equiper l'atout §6force §f!");
                    break;

                case 1:
                    if(!$player->hasPermission("atout.speed")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return;
                    }

                    if($player->getEffects()->has(VanillaEffects::SPEED())){
                        $player->getEffects()->remove(VanillaEffects::SPEED());
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien retirer l'atout §6speed §f!");
                        return;
                    }

                    $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 5*60*60*20, 0, false));
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien equiper l'atout §6speed §f!");
                    break;

                case 2:
                    if(!$player->hasPermission("atout.haste")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return;
                    }

                    if($player->getEffects()->has(VanillaEffects::HASTE())){
                        $player->getEffects()->remove(VanillaEffects::HASTE());
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien retirer l'atout §6haste §f!");
                        return;
                    }

                    $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 5*60*60*20, 0, false));
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien equiper l'atout §6haste §f!");
                    break;
            }
            return true;

        });
        $form->setTitle("§f- §6Atout §f-");
        $form->addButton("§l» §r§6Force", 0, "textures/items/iron_sword");
        $form->addButton("§l» §r§6Speed", 0, "textures/items/feather");
        $form->addButton("§1» §r§6Haste", 0, "textures/items/gold_pickaxe");
        $player->sendForm($form);
    }
    
    public function enchantItem(PlayerManager $player, $vanillaEnchant, int $price, string $customName, int $maxEnchant){
        $form = self::createCustomForm(function (PlayerManager $player, array $data = null,) use ($vanillaEnchant, $price, $customName, $maxEnchant){
            $result = $data;
            if($result === null){
                return true;
            }
            if($player->myMoney() >= $price*(int)$data[0]){
                $item = $player->getInventory()->getItemInHand();
                $item->addEnchantment(new EnchantmentInstance($vanillaEnchant, $data[0]));
                $player->getInventory()->setItemInHand($item);
                
                $money = $player->myMoney() - $price*(int)$data[0];
                $maria = Provider::database();
                $maria->query("UPDATE player SET `money` = '".$money."' WHERE `username` = '". $player->getName(). "'");
                
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien §2enchanté §fvotre item avec §c{$customName} §fpour §6" . $price*$data[0] . "");
            }else{
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous ne possedez pas asser de §1money !");
            }
            return true;
        });
        $form->setTitle("§f- §6Enchant §f-");
        $form->addSlider("combien de level voulez enchanté votre item avec §1{$customName} \n§fPour §e{$price}  §fpar niveau ? ", 1, $maxEnchant);
        $form->sendToPlayer($player);
    }
    
    public function enchant(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int|string $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            $item = $player->getInventory()->getItemInHand();
            switch($result){
                case "sharpness":
                    $this->enchantItem($player, VanillaEnchantments::SHARPNESS(), 150, "§cSharpness", 5);
                break;
                    
                case "unbreaking":
                    $this->enchantItem($player, VanillaEnchantments::UNBREAKING(), 50, "§2Unbreaking", 3);
                break;
                    
                case "protection":
                    $this->enchantItem($player, VanillaEnchantments::PROTECTION(), 100, "§8Protection", 4);
                    break;
            }
            return true;

        });
        $form->setTitle("§f- §6Enchant §f-");
        $item = $player->getInventory()->getItemInHand();
        if($item instanceof Tool){
            $form->addButton("§l» Sharpness §o§8(150 )", -1, "", "sharpness");
        }
        if($item instanceof Armor){
            $form->addButton("§l» Protection §o§8(100 )", -1, "", "protection");
        }
        if($item instanceof Armor || $item instanceof Tool){
            $form->addButton("§l» Unbreaking §o§8(50 )", -1, "", "unbreaking");
        }
        $player->sendForm($form);
    }
    
    public function alambicValid(PlayerManager $player){
        $form = self::createCustomForm(function (PlayerManager $player, array $data = null,){
            $result = $data;
            if($result === null){
                return true;
            }
            if($player->myMoney() >= 100*(int)$data[0]){
                
                $i = 1;
                while($i <= $data[0]){
                    $potion = VanillaItems::STRONG_HEALING_SPLASH_POTION();
                    $potion->getNamedTag()->setString("Stackable_Potion:solaria", "2");
                    $potion->setCustomName("§r§7Potion de §o§dheal II §r§7(x2)");
                    $player->getInventory()->addItem($potion);
                    $i++;
                }
                
                $money = $player->myMoney() - 100*(int)$data[0];
                $maria = Provider::database();
                $maria->query("UPDATE player SET `money` = '".$money."' WHERE `username` = '". $player->getName(). "'");
                
                $player->sendMessage("§§o§f[§6§l!!!§r§o]§r§f Vous avez bien §2acheté§f §8(x{$data[0]}) §7Potion §o§dheal II §r§7Stackabe §fpour §1" . 100*$data[0] . "");
            }else{
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous ne possedez pas asser de §1money !");
            }
            return true;
        });
        $form->setTitle("§f- §9Solaria §1Alambic §cMe§6rc§cur§6e §f-");
        $form->addSlider("§7Combien voulez vous de:\n§fPotion §dHeal II §fStackable §8(x2) §7\nPour §1100§8/u  §7?", 1, 16);
        $form->sendToPlayer($player);
    }
    
    public function events(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $player->teleport(new Location(91, 69, -46, Server::getInstance()->getWorldManager()->getWorldByName("kitmap"), 0, 0));
                break;
                    
                case 1:
                    $player->teleport(new Location(105, 92, -69, Server::getInstance()->getWorldManager()->getWorldByName("kitmap"), 0, 0));
                break;

                case 2:
                    $player->teleport(new Location(44, 64, -41, Server::getInstance()->getWorldManager()->getWorldByName("kitmap"), 0, 0));
                break;    
            }
            return true;

        });
        $form->setTitle("§f- §6Event §f-");
        $form->addButton("§l» §r§9Koth", 0, "textures/items/emerald");
        $form->addButton("§l» §r§eTotem", 0, "textures/items/totem");
        $form->addButton("§l» §r§5Nexus", 0, "textures/items/end_crystal");
        $form->addButton("§l» §r§7Boss §o§cSoon...§f", 0, "textures/items/egg");
        $player->sendForm($form);
    }

    public function particule(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            $particleCfg = new Config(Core::getInstance()->getDataFolder() . "player_particle.yml", Config::YAML);
            switch($result){

                case 0;
                    if($particleCfg->exists($player->getName())){
                        $particleCfg->remove($player->getName());
                        $particleCfg->save();
                    }

                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien retirer vos particules !");
                    break;


                case 1:
                    if(!$player->hasPermission("particle.redstone")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }

                    $particleCfg->set($player->getName(), "redstone");
                    $particleCfg->save();

                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fP§f Vous avez equiper la particule §eRedstone !");
                    break;

                case 2:
                    if(!$player->hasPermission("particle.lava")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }

                    $particleCfg->set($player->getName(), "lava");
                    $particleCfg->save();

                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fP§f Vous avez equiper la particule §eLava !");
                    break;

                case 3:
                    if(!$player->hasPermission("particle.fire")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }

                    $particleCfg->set($player->getName(), "fire");
                    $particleCfg->save();

                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fP§f Vous avez equiper la particule §eFire !");
                    break;

                case 4:
                    if(!$player->hasPermission("particle.water")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }

                    $particleCfg->set($player->getName(), "water");
                    $particleCfg->save();

                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fP§f Vous avez equiper la particule §eWater !");
                    break;

            }
            return true;

        });
        $form->setTitle("§f- §6Particules §f-");
        $form->addButton("§l» §r§cRetirer");
        $form->addButton("§l» §r§eRedstone");
        $form->addButton("§l» §r§eLava");
        $form->addButton("§l» §r§eFire");
        $form->addButton("§l» §r§eWater");
        $player->sendForm($form);
    }
    
    public function capes(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            $capeCfg = new Config(Core::getInstance()->getDataFolder() . "player_capes.yml", Config::YAML);
            switch($result){
                
                case 0;
                    $oldSkin = $player->getSkin();
                    $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), "", $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
                    $player->setSkin($newSkin);
                    $player->sendSkin();
                    
                    if($capeCfg->exists($player->getName())){
                        $capeCfg->remove($player->getName());
                        $capeCfg->save();
                    }
                    
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien retirer votre capes !");
                break;
           
                    
                case 1:
                    if(!$player->hasPermission("cape.golem")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }
                    
                    $oldSkin = $player->getSkin();
                    $capeData = Utils::CapeData("golem.png");
                    $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
                    
                    $player->setSkin($newSkin);
                    $player->sendSkin();
                    
                    $capeCfg->set($player->getName(), "golem.png");
                    $capeCfg->save();
                    
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez équiper la cape §eGolem §f !");
                break;
                    
                case 2:
                    if(!$player->hasPermission("cape.pickaxe")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }
                    
                    $oldSkin = $player->getSkin();
                    $cape = Utils::CapeData("pickaxe.png");
                    $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $cape, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
                    $player->setSkin($newSkin);
                    $player->sendSkin();
                    
                    $capeCfg->set($player->getName(), "pickaxe.png");
                    $capeCfg->save();
                    
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez équiper la cape §ePickaxe §f !");
                break;
                    
                case 3:
                    if(!$player->hasPermission("cape.flames")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }
                    
                    $oldSkin = $player->getSkin();
                    $cape = Utils::CapeData("flames.png");
                    $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $cape, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
                    $player->setSkin($newSkin);
                    $player->sendSkin();
                    
                    $capeCfg->set($player->getName(), "flames.png");
                    $capeCfg->save();
                    
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez équiper la cape §eFlames §f !");
                break;
                    
                case 4:
                    if(!$player->hasPermission("cape.bluecreeper")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }
                    
                    $oldSkin = $player->getSkin();
                    $cape = Utils::CapeData("blue_creeper.png");
                    $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $cape, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
                    $player->setSkin($newSkin);
                    $player->sendSkin();
                    
                    $capeCfg->set($player->getName(), "blue_creeper.png");
                    $capeCfg->save();
                    
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez équiper la cape §eCreeper Bleu §f !");
                break;
                    
                case 5:
                    if(!$player->hasPermission("cape.redcreeper")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }
                    
                    $oldSkin = $player->getSkin();
                    $cape = Utils::CapeData("red_creeper.png");
                    $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $cape, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
                    $player->setSkin($newSkin);
                    $player->sendSkin();
                    
                    $capeCfg->set($player->getName(), "red_creeper.png");
                    $capeCfg->save();
                    
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez équiper la cape §eCreeper Rouge §f !");
                break;
                
                case 6:
                    if(!$player->hasPermission("cape.enderman")){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n'avez pas les permissions !");
                        return true;
                    }
                    $oldSkin = $player->getSkin();
                    $cape = Utils::CapeData("enderman.png");
                    $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $cape, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
                    $player->setSkin($newSkin);
                    $player->sendSkin();
                    
                    $capeCfg->set($player->getName(), "enderman.png");
                    $capeCfg->save();
                    
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez équiper la cape §eEnderman §f !");
                break;    
            }
            return true;

        });
        $form->setTitle("§f- §6Capes §f-");
        $form->addButton("§l» §r§cRetirer");
        $form->addButton("§l» §r§eGolem");
        $form->addButton("§l» §r§ePickaxe");
        $form->addButton("§l» §r§eFlames");
        $form->addButton("§l» §r§eCreeper Bleu");
        $form->addButton("§l» §r§eCreeper Rouge");
        $form->addButton("§l» §r§eEnderman");
        $player->sendForm($form);
    }
    
    public function enclume(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                  $item = $player->getInventory()->getItemInHand();
                    if($item instanceof Armor || $item instanceof Tool){
                        $this->repair($player, $item);
                    }else{
                      $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous ne pouvez pas repair cette item !");
                    }
                break;
                
                case 1:
                    $item = $player->getInventory()->getItemInHand();
                    if($item->getId() > 0){
                        $this->rename($player, $item);
                    }else{
                      $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous ne pouvez pas rename cette item !");
                    }
                     
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Enclume §f-");
        $form->addButton("§l» §r§fRéparer");
        $form->addButton("§l» §r§fRenomer");
        $player->sendForm($form);
    }
    
    public function rename(PlayerManager $player, Item $item){
        $form = self::createCustomForm(function (PlayerManager $player, array $data = null,) use ($item){
            $result = $data;
            if($result === null){
                return true;
            }
            if($player->myMoney() >= 8){
                
                $item->setCustomName($data[0]);
                $this->removeMoney($player, 8);
                $player->getInventory()->setItemInHand($item);
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien votre item par §o§8{$data[0]} §f!");
            }else{
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas asser de money ! !");
            }
            return true;
        });
        $form->setTitle("§f- §6Enclume §f-");
        $form->addInput("Etes-vous sur de vouloir renomer votre item pour  §f? \nEntrée ici le nouveau nom voulue !", "La Pioche de FanLeBest");
        $form->sendToPlayer($player);
    }
    
    public function repair(PlayerManager $player, Item $item){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null) use ($item){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                  
                  if($player->myMoney() >= 6){
                    $this->removeMoney($player, 6);
                    /*$newItem = ItemFactory::getInstance()->get($item->getId(), $item->getMeta());
                    $newItem->setCustomName($item->getName());
                    if($item->hasEnchantments()){
                      foreach($item->getEnchantments() as $e){
                        $newItem->addEnchantment($e);
                      }
                    }*/
                    
                    $item->setDamage(0, true);
                    
                    $player->getInventory()->setItemInHand($item);
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous avez bien réparé votre item !");
                  }else{
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f §r§fVous n’avez pas asser de money !");
                  }
                break;
                    
                case 1:
                     $this->enclume($player);
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Enclume §f-");
        $form->setContent("Etes-vous sur de vouloir réparer votre item pour §a6  §f?");
        $form->addButton("§2Réparer");
        $form->addButton("§cRetour");
        $player->sendForm($form);
    }
    
    public function alambic(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->alambicValid($player);
                break;
            }
            return true;

        });
        $form->setTitle("§f- §9Solaria §1Alambic §cMe§6rc§cur§6e §f-");
        $form->addButton("§1§l»§r §fPotion §dHeal II §fStackable §8(x2)");
        $player->sendForm($form);
    }
    
    public function shopConfirm(PlayerManager $player, Item $item, int $price, string $customName){
        $form = self::createCustomForm(function (PlayerManager $player, array $data = null,) use ($item, $price, $customName){
            $result = $data;
            if($result === null){
                return true;
            }
            if($player->myMoney() >= $price*(int)$data[0]){
                
                $this->removeMoney($player, $price*$data[0]);
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien §2acheté§f §8(x{$data[0]}) §7$customName §fpour §e" . $price*$data[0] . "");
                $i = 1;
                while($i <= $data[0]){
                    $player->getInventory()->addItem($item);
                    $i++;
                }
            }else{
                $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous ne possedez pas asser de §emoney !");
            }
            return true;
        });
        $form->setTitle("§f- §6Shop §f-");
        $form->addSlider("§7Combien voulez vous de: {$customName} §7\nPour §e{$price}§8/u  §7?", 1, 64);
        $form->sendToPlayer($player);
    }
    
    public function shopConsum(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::COOKIE, 0, 1), 1000, "Pomme en §9Saphir");
                break;
                    
                case 1:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::GOLDEN_APPLE, 0, 1), 100, "Pomme en §eOr");
                break;
                    
                case 2:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(423, 0, 1), 100, "Join");
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Shop §f-");
        $form->setContent("Bienvenue dans le §eshop§f de §6Solaria §f\nVous avez: §2" . $player->myMoney() . "");
        $form->addButton("§e§l»§r Pomme en Saphir §o§8(x1) §r§f\n§l»§r §61000 ", 0, "textures/items/cookie");
        $form->addButton("§e§l»§r Pomme en Or §o§8(x1) §r§f\n§l»§r §6100 ", 0, "textures/items/apple_golden");
        $form->addButton("§e§l»§r Join §o§8(x1) §r§f\n§l»§r §6100 ");
        $player->sendForm($form);
    }
    
    public function shopPvp(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::SLIMEBALL, 0, 1), 3000, "Stick Of §e§kee§r§6God§e§kee§r§f");
                break;
                    
                case 1:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::BLAZE_ROD, 0, 1), 250, "Stick de §cForce");
                break;
                
                case 2:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::BLAZE_POWDER, 0, 1), 500, "Stick de §dSpeed");
                break;
                    
                case 3:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::SUGAR, 0, 1), 500, "Stick de §2TP");
                break;
                    
                case 4:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::SNOWBALL, 0, 1), 25, "§fSnowBall");
                break;
                    
                case 5:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::FEATHER, 0, 1), 1500, "Plume");
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Shop §f-");
        $form->setContent("Bienvenue dans le §eshop§f de §6Solaria §f\nVous avez: §2" . $player->myMoney() . "");
        $form->addButton("§e§l»§r Stick Of God §o§8(x1) §r§f\n§l»§r §93000 ", 0, "textures/items/slimeball");
        $form->addButton("§e§l»§r Stick de Force §o§8(x1) §r§f\n§l»§r §6250 ", 0, "textures/items/blaze_rod");
        $form->addButton("§e§l»§r Stick de Speed §o§8(x1) §r§f\n§l»§r §6500 ", 0, "textures/items/blaze_powder");
        $form->addButton("§e§l»§r Stick de TP §o§8(x1) §r§f\n§l»§r §6500 ", 0, "textures/items/sugar");
        $form->addButton("§e§l»§r SnowBall §o§8(x1) §r§f\n§l»§r §625 ", 0, "textures/items/snowball");
        $form->addButton("§e§l»§r Plume de Lévitation §o§8(x1) §r§f\n§l»§r §61500 ", 0, "textures/items/feather");
        $player->sendForm($form);
    }
    
    public function shopUtils(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::EGG, 0, 1), 1000, "Pearl de Jade");
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Shop §f-");
        $form->setContent("Bienvenue dans le §eshop§f de §6Solaria §f\nVous avez: §2" . $player->myMoney() . "");
        $form->addButton("§e§l»§r Pearl de Jade §o§8(x1) §r§f\n§l»§r §61000 ", 0, "textures/items/egg");
        $player->sendForm($form);
    }
    
    public function shopTrap(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::IRON_TRAPDOOR, 0, 1), 100, "Obsidian Trapdoor");
                break;
                    
                case 1:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::IRON_DOOR, 0, 1), 250, "Obsidian Door");
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Shop §f-");
        $form->setContent("Bienvenue dans le §eshop§f de §6Solaria §f\nVous avez: §2" . $player->myMoney() . "");
        $form->addButton("§e§l»§r Obsidian Trapdoor §o§8(x1) §r§f\n§l»§r §6100 ", 0, "textures/items/iron_trapdoor");
        $form->addButton("§e§l»§r Obsidian door §o§8(x1) §r§f\n§l»§r §6250 ", 0, "textures/items/door_iron");
        $player->sendForm($form);
    }
    
    public function shopMinerai(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT, 0, 1), 100, "Lingot de §9Saphir");
                break;
                    
                case 1:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(ItemIds::EMERALD, 0, 1), 1000, "Lingot de §2Jade");
                break;
                
                case 2:
                    $this->shopConfirm($player, ItemFactory::getInstance()->get(464, 0, 1), 25000, "Lingot de §8Platine");
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Shop §f-");
        $form->setContent("Bienvenue dans le §eshop§f de §6Solaria §f\nVous avez: §2" . $player->myMoney() . "");
        $form->addButton("§e§l»§r Saphir §o§8(x1) §r§f\n§l»§r §6100 ", 0, "textures/items/gold_ingot");
        $form->addButton("§e§l»§r Jade §o§8(x1) §r§f\n§l»§r §61000 ", 0, "textures/items/emerald");
        $form->addButton("§e§l»§r Platine §o§8(x1) §r§f\n§l»§r §625000 ", 0, "textures/items/netherite_ingot");
        $player->sendForm($form);
    }
    
    public function shop(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->shopPvp($player);
                break;
                
                case 1:
                    $this->shopConsum($player);
                break;
                    
                case 2:
                    $this->shopUtils($player);
                break;
                    
                case 3:
                    $this->shopTrap($player);
                break;
                
                case 4:
                    $this->shopMinerai($player);
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Shop §f-");
        $form->setContent("Bienvenue dans le §eshop§f de §6Solaria §f\nVous avez: §2" . $player->myMoney() . "");
        $form->addButton("§e§l»§r PvP", 0, "textures/items/gold_sword");
        $form->addButton("§e§l»§r Consumable", 0, "textures/items/cookie");
        $form->addButton("§e§l»§r Utils", 0, "textures/items/egg");
        $form->addButton("§e§l»§r Trap", 0, "textures/items/iron_trapdoor");
        $form->addButton("§e§l»§r Minerais", 0, "textures/items/gold_ingot");
        $player->sendForm($form);
    }
    
    public function boutique(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->boutiqueKey($player);
                break;
                
                case 1:
                    $this->boutiqueGrade($player);
                break;
                
                case 2:
                    $player->sendMessage("§6Error §fLes cosmétique ne sont pas disponible pour le momement merci de votre patiente: IN_DEV");
                break;
                
                case 3:
                    $player->sendMessage("§6Error §fLes cosmétique ne sont pas disponible pour le momement merci de votre patiente: IN_DEV");
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Boutique §f-");
        $form->setContent("Bienvenue dans la §eboutique§f de §6Solaria §f\nVous avez: §2" . $player->myPointBoutique() . " Point Boutique");
        $form->addButton("§e§l»§r Key");
        $form->addButton("§e§l»§r Grade");
        $form->addButton("§e§l»§r Tag");
        $form->addButton("§e§l»§r Capes");
        $player->sendForm($form);
    }
    
    public function boutiqueKey(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->boutiqueConfirm($player, 1, "Key §eSaphir", "saphir", 100);
                break;

                case 1:
                    $this->boutiqueConfirm($player, 1, "Key §eJade", "jade", 200);
                    break;

                case 2:
                    $this->boutiqueConfirm($player, 1, "Key §eRare", "rare", 250);
                    break;

                case 3:
                    $this->boutiqueConfirm($player, 1, "Key §eUltime", "saphir", 500);
                    break;
            }
            return true;

        });
        $form->setTitle("§f- §6Boutique §f-");
        $form->setContent("Bienvenue dans la §eboutique§f de §6Solaria §f\nVous avez: §2" . $player->myPointBoutique() . " Point Boutique");
        $form->addButton("§e§l»§r Key Saphir §o§f§8- §7100 PB");
        $form->addButton("§e§l»§r Key Jade §o§f§8- §7200 PB");
        $form->addButton("§e§l»§r Key Rare §o§f§8- §7250 PB");
        $form->addButton("§e§l»§r Key Ultime §o§f§8- §7500 PB");
        $player->sendForm($form);
    }
    
    public function boutiqueGrade(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    $this->boutiqueConfirm($player, 0, "Grade §eVIP", "vip", 500);
                break;
                    
                case 1:
                    $this->boutiqueConfirm($player, 0, "Grade §6VIP+", "vipplus", 700);
                break;
                    
                case 2:
                    $this->boutiqueConfirm($player, 0, "Grade §1Héro", "hero", 1000);
                break;
                    
                case 3:
                    $this->boutiqueConfirm($player, 0, "Grade §dChampion", "champion", 1500);
                break;
                    
                case 4:
                    $this->boutiqueConfirm($player, 0, "Grade §bSuprême", "supreme", 2500);
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Boutique §f-");
        $form->setContent("Bienvenue dans la §eboutique§f de §6Solaria §f\nVous avez: §2" . $player->myPointBoutique() . " Point Boutique");
        $form->addButton("§e§l»§r §eVIP §o§f§8- §7500 PB");
        $form->addButton("§e§l»§r §6VIP+ §o§f§8- §7700 PB");
        $form->addButton("§e§l»§r §1Héro §o§f§8- §71000 PB");
        $form->addButton("§e§l»§r §dChampion §o§f§8- §71500 PB");
        $form->addButton("§e§l»§r §bSuprême §o§f§8- §72500 PB");
        $player->sendForm($form);
    }
    
    public function boutiqueConfirm(PlayerManager $player, int $type, string $itemName, string $item, int $price){
        $form = self::createSimpleForm(function (PlayerManager $player, int $data = null) use ($type, $itemName, $item, $price){
            $result = $data;
            if($result === null){
                return true;
            }       
            switch($result){
                case 0:
                    switch($type){
                        case 0:
                            switch($item){
                                case "vip":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "setrank {$player->getName()} Vip");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                break;
                                    
                                case "vipplus":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "setrank {$player->getName()} Vipplus");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                break;
                                    
                                case "hero":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "setrank {$player->getName()} Héro");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                break;
                                    
                                case "champion":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "setrank {$player->getName()} Champion");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                break;
                                    
                                case "supreme":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "setrank {$player->getName()} Supreme");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                break;
                                
                            }
                        break;
                         
                        case 1:
                            switch($item){
                                case "saphir":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "givekey saphir 1 {$player->getName()}");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                break;

                                case "jade":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "givekey jade 1 {$player->getName()}");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                    break;

                                case "rare":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "givekey rare 1 {$player->getName()}");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                    break;

                                case "ultime":
                                    if($player->myPointBoutique() - $price >= 0){
                                        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), new Language("eng")), "givekey ultime 1 {$player->getName()}");
                                        self::removePb($player, $price);
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien acheter §1{$itemName} §fpour §9{$price} Point Boutique !");
                                    }else{
                                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous n'avez pas assez de §9point boutique §f!");
                                    }
                                    break;

                            }
                        break;
                    }
                break;
                case 1:
                    $this->boutique($player);
                break;
            }
            return true;

        });
        $form->setTitle("§f- §6Boutique §f-");
        $form->setContent("Êtes-vous sur de vouloir acheter: §1{$itemName} §fpour §e{$price} §f? §f\nVous avez: §2" . $player->myPointBoutique() . " Point Boutique");
        $form->addButton("§2Oui, continuer !");
        $form->addButton("§cNon, retours...");
        $player->sendForm($form);
    }
    
    public static function removePb(PlayerManager $player, int $count){
        $maria = Provider::database();
        $newCount = $player->myPointBoutique() - $count;
        $maria->query("UPDATE player SET `pointboutique` = '" . $newCount . "' WHERE `username` = '" . $player->getName() ."'");
    }
    
    public static function removeMoney(PlayerManager $player, int $count){
        $maria = Provider::database();
        $newCount = $player->myMoney() - $count;
        $maria->query("UPDATE player SET `money` = '" . $newCount . "' WHERE `username` = '" . $player->getName() ."'");
    }
    
    public function kit(PlayerManager $player){
        $form = self::createSimpleForm(function (PlayerManager $player, int|string $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            $maria = Provider::database();
            $query = $maria->query("SELECT `kit` FROM cooldown WHERE `username` = '" . $player->getName() ."'");
            $fetchAll = $query->fetchArray();
            $time = explode("|", $fetchAll[0]);
            switch($result){
                    
                case "paysan":
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §8Paysan");
                        
                    $helmet = ItemFactory::getInstance()->get(ItemIds::DIAMOND_HELMET, 0, 1);
                    $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
                    $player->getInventory()->addItem($helmet);
                        
                    $chestplate = ItemFactory::getInstance()->get(ItemIds::DIAMOND_CHESTPLATE, 0, 1);
                    $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
                    $player->getInventory()->addItem($chestplate);
                        
                    $leggings = ItemFactory::getInstance()->get(ItemIds::DIAMOND_LEGGINGS, 0, 1);
                    $player->getInventory()->addItem($leggings);
                        
                    $boots = ItemFactory::getInstance()->get(ItemIds::DIAMOND_BOOTS, 0, 1);
                    $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
                    $player->getInventory()->addItem($boots);
                        
                    $sword = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SWORD, 0, 1);
                    $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 2));
                    $player->getInventory()->addItem($sword);
                        
                    $pearl = ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL, 0, 8);
                    $player->getInventory()->addItem($pearl);
                        
                    $pot = ItemFactory::getInstance()->get(373, 15, 1);
                    $player->getInventory()->addItem($pot);
                        
                    $pot = ItemFactory::getInstance()->get(373, 32, 1);
                    $player->getInventory()->addItem($pot);
                        
                    $pot = ItemFactory::getInstance()->get(438, 22, 35);
                    $player->getInventory()->addItem($pot);  
                break;
                    
                case "vip":
                    if((int)$time[0] === 0000 || (int)$time[0] - time() <= 0){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9vip");
                        
                        $helmet = ItemFactory::getInstance()->get(ItemIds::DIAMOND_HELMET, 0, 1);
                        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
                        $player->getInventory()->addItem($helmet);
                        
                        $chestplate = ItemFactory::getInstance()->get(ItemIds::DIAMOND_CHESTPLATE, 0, 1);
                        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
                        $player->getInventory()->addItem($chestplate);
                        
                        $leggings = ItemFactory::getInstance()->get(ItemIds::DIAMOND_LEGGINGS, 0, 1);
                        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
                        $player->getInventory()->addItem($leggings);
                        
                        $boots = ItemFactory::getInstance()->get(ItemIds::DIAMOND_BOOTS, 0, 1);
                        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
                        $player->getInventory()->addItem($boots);
                        
                        $sword = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SWORD, 0, 1);
                        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 3));
                        $player->getInventory()->addItem($sword);
                        
                        $pearl = ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL, 0, 16);
                        $player->getInventory()->addItem($pearl);
                        
                        $pot = ItemFactory::getInstance()->get(373, 15, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(373, 32, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(438, 22, 35);
                        $player->getInventory()->addItem($pot);
                        
                        $newCooldown = "" . time() + 1*24*60*60 . "|" . $time[1] . "|" . $time[2] . "|" . $time[3] . "|" . $time[4] . "";
                        $maria->query("UPDATE cooldown SET `kit` = '" . $newCooldown. "' WHERE `username` = '" . $player->getName() . "'");
                    }else{
                        $timer = Utils::convertTime($time[0]);
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Veillez attendre encore §e{$timer["hours"]}h§f, §e{$timer["minuts"]}m§f, §e{$timer["seconds"]}s§f !");
                    }
                break;
                    
                case "vipplus":
                    if((int)$time[1] === 0000 || (int)$time[1] - time() <= 0){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9vip+");
                        
                        $helmet = ItemFactory::getInstance()->get(ItemIds::DIAMOND_HELMET, 0, 1);
                        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3));
                        $player->getInventory()->addItem($helmet);
                        
                        $chestplate = ItemFactory::getInstance()->get(ItemIds::DIAMOND_CHESTPLATE, 0, 1);
                        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3));
                        $player->getInventory()->addItem($chestplate);
                        
                        $leggings = ItemFactory::getInstance()->get(ItemIds::DIAMOND_LEGGINGS, 0, 1);
                        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3));
                        $player->getInventory()->addItem($leggings);
                        
                        $boots = ItemFactory::getInstance()->get(ItemIds::DIAMOND_BOOTS, 0, 1);
                        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3));
                        $player->getInventory()->addItem($boots);
                        
                        $sword = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SWORD, 0, 1);
                        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 4));
                        $player->getInventory()->addItem($sword);
                        
                        $pearl = ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL, 0, 16);
                        $player->getInventory()->addItem($pearl);
                        
                        $pot = ItemFactory::getInstance()->get(373, 15, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(373, 32, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(438, 22, 35);
                        $player->getInventory()->addItem($pot);
                        
                        $newCooldown = "" . $time[0] . "|" . time() + 1*24*60*60 . "|" . $time[2] . "|" . $time[3] . "|" . $time[4] . "";
                        $maria->query("UPDATE cooldown SET `kit` = '" . $newCooldown. "' WHERE `username` = '" . $player->getName() . "'");
                    }else{
                        $timer = Utils::convertTime($time[1]);
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Veillez attendre encore §e{$timer["hours"]}h§f, §e{$timer["minuts"]}m§f, §e{$timer["seconds"]}s§f !");
                    }
                break;
                    
                case "hero":
                    if((int)$time[2] === 0000 || (int)$time[2] - time() <= 0){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9héro");
                        
                        $helmet = ItemFactory::getInstance()->get(ItemIds::DIAMOND_HELMET, 0, 1);
                        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $player->getInventory()->addItem($helmet);
                        
                        $chestplate = ItemFactory::getInstance()->get(ItemIds::DIAMOND_CHESTPLATE, 0, 1);
                        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $player->getInventory()->addItem($chestplate);
                        
                        $leggings = ItemFactory::getInstance()->get(ItemIds::DIAMOND_LEGGINGS, 0, 1);
                        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $player->getInventory()->addItem($leggings);
                        
                        $boots = ItemFactory::getInstance()->get(ItemIds::DIAMOND_BOOTS, 0, 1);
                        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $player->getInventory()->addItem($boots);
                        
                        $sword = ItemFactory::getInstance()->get(ItemIds::GOLDEN_SWORD, 0, 1);
                        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
                        $player->getInventory()->addItem($sword);
                        
                        $pearl = ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL, 0, 16);
                        $player->getInventory()->addItem($pearl);
                        
                        $pot = ItemFactory::getInstance()->get(373, 15, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(373, 32, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(438, 22, 35);
                        $player->getInventory()->addItem($pot);
                        
                        $newCooldown = "" . $time[0] . "|" . $time[1] ."|" . time() + 1*24*60*60 . "|" . $time[3] . "|" . $time[4] . "";
                        $maria->query("UPDATE cooldown SET `kit` = '" . $newCooldown. "' WHERE `username` = '" . $player->getName() . "'");
                    }else{
                        $timer = Utils::convertTime($time[2]);
                        $player->sendMessage("§1§l§o§f[§6§l!!!§r§o]§r§f Veillez attendre encore §e{$timer["hours"]}h§f, §e{$timer["minuts"]}m§f, §e{$timer["seconds"]}s§f !");
                    }
                break;
                    
                case "champion":
                    if((int)$time[3] === 0000 || (int)$time[3] - time() <= 0){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9champion");
                        
                        $helmet = ItemFactory::getInstance()->get(ItemIds::GOLDEN_HELMET, 0, 1);
                        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($helmet);
                        
                        $chestplate = ItemFactory::getInstance()->get(ItemIds::DIAMOND_CHESTPLATE, 0, 1);
                        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($chestplate);
                        
                        $leggings = ItemFactory::getInstance()->get(ItemIds::DIAMOND_LEGGINGS, 0, 1);
                        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($leggings);
                        
                        $boots = ItemFactory::getInstance()->get(ItemIds::GOLDEN_BOOTS, 0, 1);
                        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($boots);
                        
                        $sword = ItemFactory::getInstance()->get(ItemIds::GOLDEN_SWORD, 0, 1);
                        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
                        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($sword);
                        
                        $pearl = ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL, 0, 16);
                        $player->getInventory()->addItem($pearl);
                        
                        $pot = ItemFactory::getInstance()->get(373, 15, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(373, 32, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(438, 22, 35);
                        $player->getInventory()->addItem($pot);
                        
                        $newCooldown = "" . $time[0]. "|" . $time[1] . "|" . $time[2] . "|" . time() + 1*24*60*60  . "|" . $time[4] . "";
                        $maria->query("UPDATE cooldown SET `kit` = '" . $newCooldown. "' WHERE `username` = '" . $player->getName() . "'");
                    }else{
                        $timer = Utils::convertTime($time[3]);
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Veillez attendre encore §e{$timer["hours"]}h§f, §e{$timer["minuts"]}m§f, §e{$timer["seconds"]}s§f !");
                    }
                break;
                    
                case "supreme":
                    if((int)$time[4] === 0000 || (int)$time[4] - time() <= 0){
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9suprême");
                        
                        $helmet = ItemFactory::getInstance()->get(ItemIds::GOLDEN_HELMET, 0, 1);
                        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($helmet);
                        
                        $chestplate = ItemFactory::getInstance()->get(ItemIds::GOLDEN_CHESTPLATE, 0, 1);
                        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($chestplate);
                        
                        $leggings = ItemFactory::getInstance()->get(ItemIds::GOLDEN_LEGGINGS, 0, 1);
                        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($leggings);
                        
                        $boots = ItemFactory::getInstance()->get(ItemIds::GOLDEN_BOOTS, 0, 1);
                        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($boots);
                        
                        $sword = ItemFactory::getInstance()->get(ItemIds::GOLDEN_SWORD, 0, 1);
                        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
                        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                        $player->getInventory()->addItem($sword);
                        
                        $pearl = ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL, 0, 16);
                        $player->getInventory()->addItem($pearl);
                        
                        $pot = ItemFactory::getInstance()->get(373, 15, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(373, 32, 1);
                        $player->getInventory()->addItem($pot);
                        
                        $pot = ItemFactory::getInstance()->get(438, 22, 35);
                        $player->getInventory()->addItem($pot);
                        
                        $newCooldown = "" . $time[0] . "|" . $time[1] . "|" . $time[2] . "|" . $time[3] . "|" . time() + 1*24*60*60 . "";
                        $maria->query("UPDATE cooldown SET `kit` = '" . $newCooldown. "' WHERE `username` = '" . $player->getName() . "'");
                    }else{
                        $timer = Utils::convertTime($time[4]);
                        $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Veillez attendre encore §e{$timer["hours"]}h§f, §e{$timer["minuts"]}m§f, §e{$timer["seconds"]}s§f !");
                    }
                break;
                    
                case "potion":
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9potions");
                    
                    $potion = VanillaItems::STRONG_HEALING_SPLASH_POTION();
                    $pot = ItemFactory::getInstance()->get($potion->getId(), $potion->getMeta(), 35);
                    $player->getInventory()->addItem($pot);
                break;
                    
                case "builder":
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9builder");
                        
                    $item = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::COBBLESTONE, 0, 64);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::STONE, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::WOODEN_DOOR, 0, 64);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::WOODEN_PLANKS, 0, 128+64);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::WOODEN_PRESSURE_PLATE, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::OBSIDIAN, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::GRASS, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::WOOD, 0, 128+64);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::SIGN, 0, 32);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::CRAFTING_TABLE, 0, 64);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::WOODEN_TRAPDOOR, 0, 64);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::LADDER, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::GLASS, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::SEA_LANTERN, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::GRAVEL, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::STONE_SLAB, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(ItemIds::GLOWSTONE, 0, 128);
                    $player->getInventory()->addItem($item);
                    
                    $item = ItemFactory::getInstance()->get(116, 0, 128);
                    $player->getInventory()->addItem($item);
                break;
                    
                case "tools":
                    $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien récuperé votre kit: §9tools");
                        
                    $tools = ItemFactory::getInstance()->get(ItemIds::DIAMOND_PICKAXE, 0, 1);
                    $tools->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 4));
                    $tools->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                    $player->getInventory()->addItem($tools);
                    
                    $tools = ItemFactory::getInstance()->get(ItemIds::DIAMOND_AXE, 0, 1);
                    $tools->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 4));
                    $tools->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                    $player->getInventory()->addItem($tools);
                    
                    $tools = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SHOVEL, 0, 1);
                    $tools->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 4));
                    $tools->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                    $player->getInventory()->addItem($tools);
                break;
            }

            return true;

        });
        $form->setTitle("§f- §6Kit §f-");
        $form->setContent("§6» §fBienvenue dans l'interface des §ekit §f!");
        $form->addButton("§7§l»§r §fPaysan", 0, "textures/items/diamond_sword", "paysan");
        if($player->hasPermission("grade.kit.vip")){
            $form->addButton("§e§l»§r §fVIP", 0, "textures/items/diamond_sword", "vip");
        }
        if($player->hasPermission("grade.kit.vipplus")){
            $form->addButton("§6§l»§r §fVIP+ §7/ §fBooster", 0, "textures/items/diamond_sword", "vipplus");
        }
        if($player->hasPermission("grade.kit.hero")){
            $form->addButton("§1§l»§r §fHéro", 0, "textures/items/diamond_sword", "hero");
        }
        if($player->hasPermission("grade.kit.champion")){
            $form->addButton("§d§l»§r §fChampion", 0, "textures/items/diamond_sword", "champion");
        }
        if($player->hasPermission("grade.kit.supreme")){
            $form->addButton("§b§l»§r §fSuprème", 0, "textures/items/diamond_sword", "supreme");
        }
        $form->addButton("§9§l»§r §fPotions", 0, "textures/items/potion_bottle_splash_heal", "potion");
        $form->addButton("§9§l»§r §fTools", 0, "textures/items/iron_axe", "tools");
        $form->addButton("§9§l»§r §fBuilder", 0, "textures/items/diamond_pickaxe", "builder");
        $player->sendForm($form);
    }
    
    public function report(PlayerManager $player){
        $form = self::createCustomForm(function (PlayerManager $player, array $data = null,){
            $result = $data;
            if($result === null){
                return true;
            }
            $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Votre demande à bien été envoyé. Notre équipe de §1modération §fet §cadministratif §fvérifirons dans les plus brèf délait votre §ademande§f, une réponse vous sera donné sous peut !");
            
            $motif = ["Propos déplacer", "Menace DDOS, HACK, BOOT...", "Supection de Cheat", "Autres"];
            $urgence = ["Oui", "Non"];
            
            $webhook = new WebHookManager();
            $webhook->sendEmbed("https://discord.com/api/webhooks/937380669053612093/ODtNhrJ6sr94VZvixRH7-TafTDHThZzOAiWs35PQk7PCZEtxgguwGQMvEHEn3VKcir8V", "Report de " . $player->getName() . "", "**Joueur Reporté:** " . $data[0] . "\n**Description:** " . $data[1] . "\n**Motif:** " . $motif[$data[2]] . "\n**Urgent?:** " . $urgence[$data[3]] . "", "" . $player->getName() . " report " . $data[0] . "");
            
            if($data[3] === 0){
                $webhook->sendMessage("https://discord.com/api/webhooks/937380669053612093/ODtNhrJ6sr94VZvixRH7-TafTDHThZzOAiWs35PQk7PCZEtxgguwGQMvEHEn3VKcir8V", "@everyone");
            }
            return true;
        });
        $form->setTitle("§f- §6Report §f-");
        $form->addInput("Qui voulez vous report?", "Entré un pseudo entier ex: Fanxyz1088");
        $form->addInput("Donné nous un description §9courte §fet §9compréhensible§f de votre problème");
        $form->addDropdown("Pour quel motif souhaitez-vous report?", ["Propos déplacer", "Menace DDOS, HACK, BOOT...", "Supection de Cheat", "Autres"]);
        $form->addDropdown("Est-ce §curgent§f?", ["Oui", "Non"], 1);
        $form->sendToPlayer($player);
    }
    
    public static function createSimpleForm(callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }

    public static function createCustomForm(callable $function = null) : CustomForm {
        return new CustomForm($function);
    }
    
}