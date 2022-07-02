<?php
    
namespace Solaria\Managers;

use pocketmine\player\Player;

use DaPigGuy\PiggyFactions\players\PlayerManager as PManager;

use pocketmine\Server;
use Solaria\Tasks\DatabaseAsync;
use Solaria\Utils\Provider;
use Solaria\Core;
use pocketmine\math\Vector3;

class PlayerManager extends Player{
    
    private static $scorecount = 0;
    
    public function createPlayer(){
    }
    
    public function knockBack(float $x, float $z, float $force = 0.39, ?float $verticalLimit = 0.50) : void{
		$f = sqrt($x * $x + $z * $z);
		if($f <= 0){
			return;
		}
		if(mt_rand() / mt_getrandmax() > $this->knockbackResistanceAttr->getValue()){
			$f = 1 / $f;

			$motionX = $this->motion->x / 0.18;
			$motionY = $this->motion->y / 0.36;
			$motionZ = $this->motion->z / 9;
			$motionX += $x * $f * $force;
			$motionY += 0.36;
			$motionZ += $z * $f * $force;

			$verticalLimit ??= $force;
			if($motionY > $verticalLimit){
				$motionY = $verticalLimit;
			}

			$this->setMotion(new Vector3($motionX, $motionY, $motionZ));
		}
	}
    
    public function sendScoreboard(){
        $scoreboard = new ScoreboardManager($this);
        
        $allPlayer = count($this->getServer()->getOnlinePlayers());
        $maxPlayer = $this->getServer()->getMaxPlayers();       
        
        $scoreboard->addScoreboard("§6§lSolaria");
        $scoreboard->setLine(0, "§f  ");
        $scoreboard->setLine(1, "§a§l»   Profile");
        $scoreboard->setLine(2, "§f" . $this->getName() . "");
        $scoreboard->setLine(3, "§fJetons : §7" . $this->myMoney(). "");
        $scoreboard->setLine(4, "§fFaction : §7" . $this->getPlayerFaction() . "");
        $scoreboard->setLine(5, "§l§d»   Serveur");
        $scoreboard->setLine(6, "§fTPS : §7" . (int)$this->getServer()->getTicksPerSecond() . "");
        $scoreboard->setLine(7, "§fSlot : §7" . $allPlayer . "§f/§7" . $maxPlayer . "");
        $scoreboard->setLine(8, "  §f");
        $scoreboard->setLine(9, "§6§l»§r§o§7 solariamc.eu");
        
    }

    public function addMoney(int $count = 0){
        $maria = Provider::database();
        $money = $this->myMoney() + $count;
        Provider::query("UPDATE player SET `money` = '".$money."' WHERE `username` = '". $this->getName(). "'");
        return true;
    }

    public function removeMoney(int $count = 0){
        $money = $this->myMoney() - $count;
        Provider::query("UPDATE player SET `money` = '".$money."' WHERE `username` = '". $this->getName(). "'");
        return true;
    }
    
    public function myMoney(){
        $result = Provider::database()->query("SELECT `money` FROM player WHERE `username` = '". $this->getName() . "'");
        $fetchAll = $result->fetchArray();
        return (float)$fetchAll[0];
    }
    
    public function myPointBoutique(){
        $result = Provider::database()->query("SELECT `pointboutique` FROM player WHERE `username` = '". $this->getName() . "'");
        return (float)$result->fetchArray()[0];
    }
    
    
    public function getPlayerFaction(): string {
        $pfaction = PManager::getInstance()->getPlayer($this);
        $faction = $pfaction === null ? null : $pfaction->getFaction();
        if (!is_null($faction)) {
             return $faction->getName();
        } else return "...";
    }
    
    public function isBanned(){
        $maria = Provider::database();

        $result = $maria->query("SELECT * FROM bans WHERE `username` = '" . $this->getName() . "'");
        $data = $result->fetchArray();
        if(!$data === false) return $data;
        
        $result = $maria->query("SELECT * FROM bans WHERE `uuid` = '" . $this->getUniqueId() . "'");
        $data = $result->fetchArray();
        if(!$data === false) return $data;
        
        $result = $maria->query("SELECT * FROM bans WHERE `IP` = '" . $this->getNetworkSession()->getIp() . "'");
        $data = $result->fetchArray();
        if(!$data === false) return $data;
        
        $result = $maria->query("SELECT * FROM bans WHERE `xuid` = '" . $this->getPlayerInfo()->getXuid() . "'");
        $data = $result->fetchArray();
        if(!$data === false) return $data;

        return false;
    }
    
    public function isMuted(){
        $maria = Provider::database();
        $result = $maria->query("SELECT * FROM mutes WHERE `username` = '" . $this->getName() . "'");
        $data = $result->fetchArray();
        if(!$data === false) return $data;
        
        return false;
    }
}