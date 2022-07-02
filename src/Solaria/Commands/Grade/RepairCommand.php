<?php
    
namespace Solaria\Commands\Grade;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\ModsForm;

use pocketmine\item\Armor;
use pocketmine\item\Tool;
use pocketmine\item\ItemFactory;

class RepairCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("repair", "§o§6Solaria §7» §8Accedez au repair", "/repair | /repair all", []);
        $this->setPermission("grade.repair");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("grade.repair")) return $this->errorManager()->noPerms($sender);
            
            if(!isset($args[0])){
                $ItemInHand = $sender->getInventory()->getItemInHand();
                
                if($ItemInHand instanceof Armor || $ItemInHand instanceof Tool){
                    /*$newItem = ItemFactory::getInstance()->get($ItemInHand->getId(), $ItemInHand->getMeta(), 1);
                    $newItem->setCustomName($ItemInHand->getName());
                    if($ItemInHand->hasEnchantments()){
                        foreach($ItemInHand->getEnchantments as $enchant){
                            $newItem->addEnchantment($enchant);
                        }
                    }*/
                    $ItemInHand->setDamage(0, true);
                    $sender->getInventory()->setItemInHand($ItemInHand);
                    $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien §1repair §fvotre §7item§f !");
                }else{
                    $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f L'item en main n'est pas un item durable !");
                }
            }else if(isset($args[0]) && $args[0] === "all"){
                foreach($sender->getInventory()->getContents() as $index => $item){
                    if($item instanceof Tool || $item instanceof Armor){
                        $item->setDamage(0, true);
                        $sender->getInventory()->setItem($index, $item);
                    }
                }
                
                $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien §1repair §fvotre §7stuff§f !");
            }
        }
    }

}