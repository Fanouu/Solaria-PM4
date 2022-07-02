<?php
    
namespace Solaria\Utils;

use MySQLi;
use pocketmine\Server;
use pocketmine\thread\Thread;
use Solaria\Tasks\DatabaseAsync;
use Solaria\Tasks\QueryAsync;
use Solaria\Core;

class Provider{

    private static $database = [];
    private static $mysqli;
    
    public static function database(): \SQLite3{
        return new \SQLite3(Core::getInstance()->getDataFolder() . "Solaria.db");
    }

    public static function query(string $text){
        return self::database()->query($text);
    }

    public static function querie(string $text){
        Server::getInstance()->getAsyncPool()->submitTask(new QueryAsync($text));
    }
}