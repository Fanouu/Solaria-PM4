<?php
    
namespace Solaria\Commands\Staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use Solaria\Entities\JadeBox;
use Solaria\Entities\RareBox;
use Solaria\Entities\UltimeBox;
use Solaria\Managers\PlayerManager;
use Solaria\Commands\SolariaCommand;
use Solaria\Entities\VoteBox;
use Solaria\Entities\SaphirBox;
use Solaria\Core;
use Solaria\Utils\Utils;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\utils\SingletonTrait;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\entity\Location;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;

class BoxCommand extends Command{
    use SolariaCommand;

    private $plugin;

    public function __construct() {
        parent::__construct("spawnbox", "§o§6Solaria §7» §8Box command", "/spawnbox <box>", []);
        $this->setPermission("staff.spawnbox");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($sender instanceof PlayerManager){
            if(!$sender->hasPermission("staff.spawnbox")) return $this->errorManager()->noPerms($sender);
            if(!isset($args[0])) return $this->errorManager()->noArgs($sender, "/spawnbox <box>");
            
            $nbt = $this->createBaseNBT($sender->getLocation(), null);
            
            if($args[0] === "vote"){
                
                $path = Core::getInstance()->getDataFolder() . "textures/vote_box.png";
                $data = Utils::PNGtoBYTES($path);
                $cape = "";
                $path = Core::getInstance()->getDataFolder() . "models/box.geo.json";
                $geometry = file_get_contents($path);

                $skin = new Skin($this->getName(), $data, $cape, "geometry.box", $geometry);
                $entity = new VoteBox($sender->getLocation(), $skin, $nbt);
                $entity->spawnToAll();
            
                $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien fait spawn une box !");
            }
            
            if($args[0] === "saphir"){
                
                $path = Core::getInstance()->getDataFolder() . "textures/saphir_box.png";
                $data = Utils::PNGtoBYTES($path);
                $cape = "";
                $path = Core::getInstance()->getDataFolder() . "models/saphir.geo.json";
                $geometry = file_get_contents($path);

                $skin = new Skin($this->getName(), $data, $cape, "geometry.box", $geometry);
                $entity = new SaphirBox($sender->getLocation(), $skin, $nbt);
                $entity->spawnToAll();
            
                $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien fait spawn une box !");
            }

            if($args[0] === "jade"){
                $entity = new JadeBox($sender->getLocation());
                $entity->spawnToAll();

                $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien fait spawn une box !");
            }

            if($args[0] === "rare"){
                $entity = new RareBox($sender->getLocation());
                $entity->spawnToAll();

                $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien fait spawn une box !");
            }

            if($args[0] === "ultime"){
                $entity = new UltimeBox($sender->getLocation());
                $entity->spawnToAll();

                $sender->sendMessage("§o§f[§6§l!!!§r§o]§r§f Vous avez bien fait spawn une box !");
            }
        }
    }
    
    public function createBaseNBT(Vector3 $pos, ?Vector3 $motion = null, float $yaw = 0.0, float $pitch = 0.0): CompoundTag {
        return CompoundTag::create()
            ->setTag("Pos", new ListTag([
                new DoubleTag($pos->x),
                new DoubleTag($pos->y),
                new DoubleTag($pos->z)
            ]))
            ->setTag("Motion", new ListTag([
                new DoubleTag($motion !== null ? $motion->x : 0.0),
                new DoubleTag($motion !== null ? $motion->y : 0.0),
                new DoubleTag($motion !== null ? $motion->z : 0.0)
            ]))
            ->setTag("Rotation", new ListTag([
                new FloatTag($yaw),
                new FloatTag($pitch)
            ]));
    }
}