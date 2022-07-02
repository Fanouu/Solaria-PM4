<?php
    
namespace Solaria\Commands;

use Solaria\Managers\ErrorManager;
use Solaria\Managers\MessageManager;
use Solaria\Managers\WebHookManager;
use Solaria\Utils\Provider;
use Solaria\Utils\Utils;

trait SolariaCommand{
    
    public function errorManager(): ErrorManager{
        return new ErrorManager();
    }
    
    public function messageManager(): MessageManager{
        return new MessageManager();
    }
    public function webhookManager(): MessageManager{
        return new WebHookManager();
    }
    
    public function database(){
        return Provider::database();
    }
    
    public function utils(): Utils{
        return new Utils();
    }
}