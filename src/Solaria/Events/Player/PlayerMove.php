<?php
    
namespace Solaria\Events\Player;

use pocketmine\utils\Config;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\LavaParticle;
use pocketmine\world\particle\RedstoneParticle;
use pocketmine\world\particle\WaterParticle;
use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;

use Solaria\Managers\MessageManager;
use Solaria\Utils\Utils;
use Solaria\Tasks\PurifTask;
use Solaria\Tasks\MoneyZoneTask;
use Solaria\Core;

class PlayerMove implements Listener{
    use PlayerListener;
    
    public static $teleport = [];
    public static $purif = [];
    public static $moneyzone = [];
    
    public function PlayerMoveEvent(PlayerMoveEvent $event){
        
        $player = $event->getPlayer();
        $from = $event->getFrom();
        $to = $event->getTo();
        $pname = $player->getName();

        $particleCfg = new Config(Core::getInstance()->getDataFolder() . "player_particle.yml", Config::YAML);
        if($particleCfg->exists($pname) && !is_null($particleCfg->get($pname))){
            $particle = match ($particleCfg->get($pname)){
              "redstone" => new RedstoneParticle(),
              "lava" => new LavaParticle(),
              "fire" => new FlameParticle(),
              "water" => new WaterParticle()
            };

            $player->getWorld()->addParticle($player->getPosition(), $particle);
        }
        
        if(isset(self::$teleport[$player->getName()])){
            if((int)$to->x > (int)$from->x || (int)$to->x < (int)$from->x || (int)$to->z > (int)$from->z || (int)$to->z < (int)$from->z){
                self::$teleport[$player->getName()] = true;
            }
        }
        if(Utils::isInPos($player, "-32:69:86", "-27:73:81", "kitmap")){
            if(!isset(self::$purif[$pname])){
                self::$purif[$pname] = true;
                Core::getInstance()->getScheduler()->scheduleRepeatingTask(new PurifTask($player, $pname, 0), 20);
            }
        }else if(!Utils::isInPos($player, "-32:69:86", "-27:73:81", "kitmap")){
            if(isset(self::$purif[$pname])){
                unset(self::$purif[$pname]);
            }
        }
    }
}