<?php
    
namespace Solaria\Events\Player;

use Solaria\Managers\MessageManager;
use Solaria\Managers\ErrorManager;
use Solaria\Utils\Provider;

trait PlayerListener{
    public function database(){
        return Provider::database();
    }
    
    public function messageManager(): MessageManager{
        return new MessageManager();
    }
    
    public function errorManager(): ErrorManager{
        return new ErrorManager();
    }
}