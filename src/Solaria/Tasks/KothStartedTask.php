<?php

namespace Solaria\Tasks;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\Task;
use Solaria\Core;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\enchantment\EnchantmentInstance;
use Solaria\Utils\Utils;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

use Solaria\Managers\KothManager;

class KothStartedTask extends Task{

    private $plugin;
    
    public static $number = 0;

    public function __construct(){
        $this->plugin = Core::getInstance();
        $this->koth_data = new Config($this->plugin->getDataFolder() . "Events/koth.json", Config::JSON);
    }

    public function onRun(): void{
        
        $minutes = KothManager::getInstance()->getRestantTimeForCapture("m");
        $sec = KothManager::getInstance()->getRestantTimeForCapture("s");
        
        if(self::$number == 100){
            
            foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if($player->getName() === KothManager::getInstance()->get("capturBy")){
                $helmet = ItemFactory::getInstance()->get(ItemIds::GOLD_HELMET, 1);
                    $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                    $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                    $player->getInventory()->addItem($helmet);

                    $chestplate = ItemFactory::getInstance()->get(ItemIds::GOLD_CHESTPLATE, 1);
                    $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                    $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                    $player->getInventory()->addItem($chestplate);
                    $leggings = ItemFactory::getInstance()->get(ItemIds::GOLD_LEGGINGS, 1);
                    $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                    $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                    $player->getInventory()->addItem($leggings);
                    $boots = ItemFactory::getInstance()->get(ItemIds::GOLD_BOOTS, 1);
                    $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
                    $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
                    $player->getInventory()->addItem($boots);

                    $sword = ItemFactory::getInstance()->get(ItemIds::GOLD_SWORD, 1);
                    $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
                    $player->getInventory()->addItem($sword);
            }
            }
          
          Core::getInstance()->getServer()->broadcastTitle("§1GG§f," .KothManager::getInstance()->get("capturBy") . " ", "Qui remporte le §cKOTH");
          KothManager::getInstance()->stop();
          $this->getHandler()->cancel();
            
            
        }
        
        if(KothManager::getInstance()->get("capturBy") != "undefined"){
      
            self::$number++;
          
            if($minutes <= 2){
            self::$number++;
        }
          
          foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if($player->getName() === KothManager::getInstance()->get("capturBy")){
                $player->sendPopup($this->changeBar(self::$number));
            }
          }
          
        }
        if($minutes == 0){
          $this->getHandler()->cancel();
          Core::getInstance()->getServer()->broadcastMessage("§1[§9§l!!!§r§1] §fL'événement Koth viens de ce terminé !");
            KothManager::getInstance()->stop();
        }

        if($minutes == 5 and($sec == 59)){
            Core::getInstance()->getServer()->broadcastMessage("§1[§9§l!!!§r§1] §fL'événement Koth ce termine dans §c5 minutes §f!");
        }

        if($minutes == 2 and($sec == 59)){
            Core::getInstance()->getServer()->broadcastMessage("§1[§9§l!!!§r§1] §fBonus de Koth §apoint doublé  §f!");
        }

        if($minutes == 10 and($sec == 59)){
            Core::getInstance()->getServer()->broadcastMessage("§1[§9§l!!!§r§1] §fL'événement Koth ce termine dans §c10 minutes §f!");
        }

    }
    
    public function changeBar($number){
      
      return str_repeat("§1|", self::$number) . str_repeat("§c|", 100 - self::$number) . " §8(".self::$number."%%)";
    }
}