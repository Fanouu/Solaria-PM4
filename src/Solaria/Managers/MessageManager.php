<?php
    
namespace Solaria\Managers;

use Solaria\Managers\PlayerManager;
use pocketmine\console\ConsoleCommandSender;

class MessageManager{
    
    public $messages = [
        "player_join" => "§f[§2+§f] §2{player}",
        "first_join" => "§fBienvenue à §e{player} §fqui rejoins pour la première fois §6Solaria §f!",
        "private_msg_join" => "§l§7          §6SolariaMc Network  \n§1§lDiscord: §fdiscord.gg/solariamc\n§b§lSite: §fSoon\n§aVote: §fSoon\n§f» §7Bon jeux sur §6Solaria §7!",
        "player_quit" => "§f[§4-§f] §4{player}",
        "tp_spawn_succes" => "§o§f[§6§lSolaria§r§o]§r§f Vous avez bien été téléporté au §1spawn §f!",
        "tp_expired" => "§o§f[§6§lSolaria§r§o]§r§f Vote demande de téléportation à expiré",
        "tp_coold" => "§o§f[§6§lSolaria§r§o]§r§f Téléportation dans §1{time} secondes §f!",
        "tp_cancel" => "§o§f[§6§lSolaria§r§o]§r§f Téléportation §1annulée §f!",
        "bowpunch_coold" => "§fVeillez attendre §1{time} secondes §favant d'utiliser §eArc Punch §f!"
    ];
        
    public function getMessage(PlayerManager|ConsoleCommandSender $player, string $messages, bool $replace = false){
        
        if($replace === false) return $this->messages[$messages];
        
        if($replace === true) return str_replace(["{player}"], [$player->getName()], $this->messages[$messages]);
    }
}