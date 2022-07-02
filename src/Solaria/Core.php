<?php
    
namespace Solaria;

use pocketmine\plugin\PluginBase;

#Solaria Use
use Solaria\Utils\Provider;
use Solaria\Utils\Loader;
use Solaria\Protection\Protection;
use Solaria\Managers\KothManager;

class Core extends PluginBase{
    
    private static $instance;
    public $combatTime = [];
    
    protected function onEnable(): void{
        
        $this->getLogger()->info("Solaria Core is loading...");
        self::$instance = $this;
        @mkdir($this->getDataFolder() . "Events");
        Loader::loadEvents();
        Loader::loadTables();
        Loader::loadTasks();
        Loader::loadCommands();
        Loader::registerEntities();
        Loader::registerItems();
        Loader::registerBlocks();
        Protection::startProtection();
        $this->saveResource("models/box.geo.json");
        $this->saveResource("models/so_box.json");
        $this->saveResource("models/saphir.geo.json");
        $this->saveResource("textures/vote_box.png");
        $this->saveResource("textures/jade_box.png");
        $this->saveResource("textures/ultime_box2.png");
        $this->saveResource("textures/saphir_box.png");
        $this->saveResource("textures/event_box.png");
        $this->saveResource("textures/rare_box.png");
        $this->saveResource("textures/ultime_box.png");
        $this->saveResource("textures/op_box.png");
        $this->saveResource("capes/red_creeper.png");
        $this->saveResource("capes/blue_creeper.png");
        $this->saveResource("capes/enderman.png");
        $this->saveResource("capes/pickaxe.png");
        $this->saveResource("capes/golem.png");
        $this->saveResource("capes/flames.png");
        $this->getServer()->getWorldManager()->loadWorld("KothInferno");
        $this->getServer()->getWorldManager()->loadWorld("KothMEDIEVAL");
        $this->getServer()->getWorldManager()->loadWorld("Mine");
        $this->getLogger()->info("Solaria Core is opérationel !");

    }

    public static function getInstance(): Core{
        return self::$instance;
    }
}