<?php

namespace Solaria\Managers;

use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\Server;

use Solaria\Core;
use Solaria\Tasks\KothStartedTask;

class KothManager{
  
  private $plugin;
  
  public function __construct(){
    $this->plugin = Core::getInstance();
    $this->koth_data = new Config($this->plugin->getDataFolder() . "Events/koth.json", Config::JSON);
  }
    
  public function getRestantTime($key){
      $timer = intval($this->koth_data->get("koth_nexTime") - time());
      $heures = intval(abs($timer / 3600));
      $minutes = intval(abs($timer / 60) % 60);
      $secondes = intval(abs($timer  - $minutes * 60));
      if($key == "h") return $heures;
      if($key == "m") return $minutes;
      if($key == "s") return $secondes;
    }
    
    public function getRestantTimeForCapture($key){
      $timer = intval($this->koth_data->get("LimiteKothForCapture") - time());
      $heures = intval(abs($timer / 3600));
      $minutes = intval(abs($timer / 60) % 60);
      $secondes = intval(abs($timer  - $minutes * 60));
      if($key == "h") return $heures;
      if($key == "m") return $minutes;
      if($key == "s") return $secondes;
  }
  
  public function getKothEnable(){
      if($this->koth_data->get("koth_enable") == true){
          return "§aEvents Koth Disponible";
      }

      if($this->koth_data->get("koth_enable") == false){
          $timer = intval($this->koth_data->get("koth_nexTime") - time());
          $heures = intval(abs($timer / 3600));
          $minutes = intval(abs($timer / 60) % 60);
          $secondes = intval(abs($timer  - $minutes * 60));
          return "§cDisponible dans§7 " . $heures . "h, " . $minutes . "m";


      }
    
  }

  public function start(){
    Core::getInstance()->getServer()->broadcastTitle("§cKOTH", "§c/event §fpour ce téléporté au §cKOTH §f!");
    $this->plugin->getScheduler()->scheduleRepeatingTask(new KothStartedTask($this->plugin), 20);
    $this->set("koth_enable", true);
    $this->set("capturBy", "undefined");
    $this->set("LimiteKothForCapture", time() + 15*60);
    KothStartedTask::$number = 0;
  }

  public function stop(){
    $this->set("koth_enable", false);
    $this->set("capturBy", "undefined");
    $this->set("koth_nexTime", 0);
  }

  public function get($key){
    return $this->koth_data->get($key);
  }

  public function set($key, $keys){
    $this->koth_data->set($key, $keys);
    $this->koth_data->save();
  }
    
    public static function getInstance(): KothManager{
        return new KothManager();
    }
}