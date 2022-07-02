<?php
    
namespace Solaria\Commands\Grade;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Forms\Form\ModsForm;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\block\tile\EnderChest;
use pocketmine\block\tile\TileFactory;
use pocketmine\block\inventory\EnderChestInventory;
use pocketmine\block\BlockFactory;

class EcCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("ec", "§o§6Solaria §7» §8Accedez a votre EnderChest", "/ec", ["enderchest"]);
        $this->setPermission("grade.ec");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("grade.ec")) return $this->errorManager()->noPerms($sender);
            $x = (int)floor($sender->getLocation()->x);
            $y = (int)floor($sender->getLocation()->y) - 4;
            $z = (int)floor($sender->getLocation()->z);
            $nbt = CompoundTag::create()
                ->setTag("id", new StringTag("Chest"))
                ->setTag("CustomName", new StringTag("EnderChest"))
                ->setTag("x", new IntTag($x))
                ->setTag("y", new IntTag($y))
                ->setTag("z", new IntTag($z));
                      
			$tile = TileFactory::getInstance()->createFromData($sender->getWorld(), $nbt);
            $block = BlockFactory::getInstance()->get(130, 0);
			$sender->getWorld()->createBlockUpdatePackets([$block]);
			$sender->setCurrentWindow(new EnderChestInventory($tile->getPosition(), $sender->getEnderInventory()));
        }
    }

}