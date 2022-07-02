<?php
    
namespace Solaria\Events\Player;

use Solaria\Events\Player\PlayerListener;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Server;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use Solaria\Managers\MessageManager;
use Solaria\Utils\Provider;
use Solaria\Core;

class PlayerDeath implements Listener{
    use PlayerListener;
    
    public static $teleport = [];
    
    public function PlayerDeathEvent(PlayerDeathEvent $event){
        
        $entity = $event->getEntity();
        $player = $event->getPlayer();
        $event->setDeathMessage("");
        
        if(isset(Core::getInstance()->combatTime[$player->getName()])){
            unset(Core::getInstance()->combatTime[$player->getName()]);
        }
        
        $cause = $entity->getLastDamageCause();
        if($cause instanceof EntityDamageByEntityEvent){
            if($cause->getCause() === EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK){
                $killers = $player->getLastDamageCause()->getDamager();
                $killer = $player->getLastDamageCause()->getDamager()->getName();
                $money = mt_rand(10, 30);
                $money = $killers->myMoney() + $money;
                $maria = Provider::database();
                $maria->query("UPDATE player SET `money` = '".$money."' WHERE `username` = '". $killer. "'");
            
                Server::getInstance()->broadcastMessage("§o§f[§6§lKill§r§o]§r§f §c" . $player->getName() . " §fa été tué par §2$killer");
            }
        }
    }
}