<?php
    
namespace Solaria\Utils;

use pocketmine\entity\Skin;
use pocketmine\utils\Config;
use Solaria\Managers\PlayerManager;
use Solaria\Core;

class Utils{

    public static function loadCapes(PlayerManager $player){
        $capeCfg = new Config(Core::getInstance()->getDataFolder() . "player_capes.yml", Config::YAML);

        if($capeCfg->exists($player->getName()) && !is_null($capeCfg->get($player->getName()))){
            $oldSkin = $player->getSkin();
            $capeData = Utils::CapeData($capeCfg->get($player->getName()));
            $newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());

            $player->setSkin($newSkin);
            $player->sendSkin();
        }
    }
    
    public static function CapeData($path) {
        $path = Core::getInstance()->getDataFolder() . "capes/" . $path;

        $image = @imagecreatefrompng($path);
        if ($image === false) {
            throw new Exception("Couldn't load image");
        }

        $size = @imagesx($image) * @imagesy($image) * 4;
        if ($size !== 64 * 32 * 4) {
            throw new Exception("Invalid cape size");
        }

        $cape_data = "";
        for ($y = 0, $height = imagesy($image); $y < $height; $y++) {
            for ($x = 0, $width = imagesx($image); $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $cape_data .= pack("c", ($color >> 16) & 0xFF) //red
                    . pack("c", ($color >> 8) & 0xFF) //green
                    . pack("c", $color & 0xFF) //blue
                    . pack("c", 255 - (($color & 0x7F000000) >> 23)); //alpha
            }
        }

        imagedestroy($image);
        return $cape_data;
    }
    
    public static function convertTime(int $time){
        $timer = $time - time();
		$day = floor($timer / 86400);
		$hourSeconds = $timer % 86400;
		$hour = floor($hourSeconds / 3600);
		$minuteSec = $hourSeconds % 3600;
		$minute = floor($minuteSec / 60);
		$remainingSec = $minuteSec % 60;
		$second = ceil($remainingSec);
        return [
            "day" => $day,
            "hours" => $hour,
            "minuts" => $minute,
            "seconds" => $second
        ];
    }
    public static function isInPos(PlayerManager $player, string $posOne, string $posTwo, string $world) {

        $pos1 = explode(":", $posOne);
        $pos2 = explode(":", $posTwo);

        $minX = min($pos1[0], $pos2[0]);
        $maxX = max($pos1[0], $pos2[0]);
        $minY = min($pos1[1], $pos2[1]);
        $maxY = max($pos1[1], $pos2[1]);
        $minZ = min($pos1[2], $pos2[2]);
        $maxZ = max($pos1[2], $pos2[2]);

        if($player->getLocation()->x >= $minX && $player->getLocation()->x <= $maxX
            && $player->getLocation()->y >= $minY && $player->getLocation()->y <= $maxY
            && $player->getLocation()->z >= $minZ && $player->getLocation()->z <= $maxZ) {
            if($player->getWorld()->getFolderName() === $world){
              return true;  
            } else return false;

        } else return false;
    }
    
    public static function PNGtoBYTES($path) : string{
        $img = @imagecreatefrompng($path);
        $bytes = "";
        for ($y = 0; $y < (int) @getimagesize($path)[1]; $y++) {
            for ($x = 0; $x < (int) @getimagesize($path)[0]; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $bytes .= chr(($rgba >> 16) & 0xff) . chr(($rgba >> 8) & 0xff) . chr($rgba & 0xff) . chr(((~(($rgba >> 24))) << 1) & 0xff);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }
}