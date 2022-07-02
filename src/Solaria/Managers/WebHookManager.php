<?php
    
namespace Solaria\Managers;

use Solaria\Managers\WebHookAPI\Embed;
use Solaria\Managers\WebHookAPI\Message;
use Solaria\Managers\WebHookAPI\Webhook;

class WebHookManager{
    
    public function sendEmbed(string $url, string|bool $title = false, string|bool $description = false, string|bool $footer = false, bool $timestamp = false){
        $WebHook = new Webhook($url);
        $msg = new Message();
        
        $embed = new Embed();
        if($title !== false) $embed->setTitle($title);
        if($description !== false) $embed->setDescription($description);
        if($footer !== false) $embed->setFooter($footer);
        if($timestamp === true) $embed->setTitemstamp(date());   
        $msg->addEmbed($embed);
        
        $WebHook->send($msg);
    }
    
    public function sendMessage(string $url, string $message = "null"){
        $WebHook = new Webhook($url);
        $msg = new Message();
        $msg->setContent($message);
        
        $WebHook->send($msg);
    }
}