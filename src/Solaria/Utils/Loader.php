<?php

namespace Solaria\Utils;

use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;
use pocketmine\block\WoodenPressurePlate;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use Solaria\Core;
use Solaria\Utils\Provider;
use Solaria\Events\Player\{PlayerCreation, PlayerJoin, PlayerQuit, PlayerLogin, PlayerMove, PlayerInteract, PlayerDeath, PlayerItemUse, CommandPreprocess, PlayerExhaust, PlayerItemConsume, PlayerChat};
use Solaria\Events\Entity\{ProjectileLaunch, EntityDamage, ProjectileHit, EntityDamageByEntity};
use Solaria\Events\Listener\{KothListener};
use Solaria\Events\Block\{BlockBreak};

use Solaria\Tasks\{ClearLaggTask,
    MineReset,
    ScoreboardTask,
    UpdateNetwork,
    AutoBroadcasterTask,
    KothTask,
    SolariaLoggerTask};
use Solaria\Entities\{FloatingText,
    JadeBox,
    RareBox,
    UltimeBox,
    VoteBox,
    SaphirBox,
    EnderPearl as SEnderPearl,
    EggEntity};

use Solaria\Commands\Staff\{ForceClearCommand,
    KickCommand,
    BanCommand,
    BanListCommand,
    FloatingTextCommand,
    AddMoneyCommand,
    BoxCommand,
    AddPbCommand,
    GiveKeyCommand,
    ForceKothCommand,
    MuteCommand,
    RedemCommand,
    UnmuteCommand,
    UnbanCommand};
use Solaria\Commands\Joueur\{AtoutCommand,
    ParticuleCommand,
    SpawnCommand,
    MsgCommand,
    NightVisionCommand,
    KitCommand,
    ReportCommand,
    MyMoneyCommand,
    BoutiqueCommand,
    MyPbCommand,
    PurifCommand,
    EventCommand,
    VoteCommand,
    ShopCommand,
    MinageCommand,
    PayCommand,
    TopMoneyCommand,
    CapesCommand};
use Solaria\Commands\Grade\{MineVipCommand, RepairCommand, EcCommand};

use Solaria\Items\{MercureSword, SaphirSword, JadeSword, JadeHelmet, JadeChestplate, JadeLeggings, JadeBoots, SaphirHelmet, SaphirChestplate, SaphirLeggings, SaphirBoots, EnderItem, Egg};

use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use pocketmine\entity\Human;
use pocketmine\item\ItemFactory;
use pocketmine\inventory\CreativeInventory;

class Loader{

    public static function loadEvents(){

        $Events = [
            new PlayerCreation(),
            new PlayerJoin(),
            new PlayerQuit(),
            new ProjectileLaunch(),
            new EntityDamage(),
            new PlayerLogin(),
            new PlayerMove(),
            new PlayerInteract(),
            new PlayerDeath(),
            new PlayerItemUse(),
            new CommandPreprocess(),
            new PlayerExhaust(),
            new PlayerItemConsume(),
            new KothListener(),
            new ProjectileHit(),
            new EntityDamageByEntity(),
            new BlockBreak(),
            new PlayerChat()
        ];

        foreach($Events as $ev => $event){
            Core::getInstance()->getServer()->getPluginManager()->registerEvents($event, Core::getInstance());
        }
        $count = count($Events);
        Core::getInstance()->getLogger()->info("§e$count §fEvent(s) load");
    }

    public static function loadTables(){
        $Tables = [
            "CREATE TABLE IF NOT EXISTS player (`username` TEXT, `uuid` TEXT, `IP` TEXT, `money` INT, `rank` TEXT, `permission` TEXT, `pointboutique` INT)",
            "CREATE TABLE IF NOT EXISTS bans (`username` TEXT, `uuid` TEXT, `IP` TEXT, `xuid` TEXT, `time` INT, `reason` TEXT, `staff` TEXT)",
            "CREATE TABLE IF NOT EXISTS cooldown (`username` TEXT, `kit` TEXT)",
            "CREATE TABLE IF NOT EXISTS mutes (`username` TEXT, `time` INT)"
        ];

        foreach($Tables as $ta => $table){
            Provider::query($table);
        }
        
        $count = count($Tables);
        Core::getInstance()->getLogger()->info("§e$count §fTable(s) load");
    }
    
    public static function loadTasks(){

        $Tasks = [
            new UpdateNetwork(),
            new AutoBroadcasterTask(),
            //new KothTask(),
            new SolariaLoggerTask(),
            new ClearLaggTask()
        ];

        foreach($Tasks as $ta => $task){
            Core::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20);
        }

        Core::getInstance()->getScheduler()->scheduleRepeatingTask(new MineReset(), 15*60*20);
        Core::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreboardTask(), 5*20);

        $count = count($Tasks);
        Core::getInstance()->getLogger()->info("§e$count §fTask(s) load");
    }
    
    public static function loadCommands(bool $unload = true){
        
        if($unload === true){
            $unloads = ["kick","msg","ban","ban-ip", "banlist", "kill", "suicide", "particle", "me"];
            $commands = Core::getInstance()->getServer()->getCommandMap();

            foreach ($unloads as $cmds) {
                $command = $commands->getCommand($cmds);
                if ($command !== null) {
                    $command->setLabel("cmdold_" . $cmds);
                    $commands->unregister($command);
                }
            }
            $counts = count($unloads);
            Core::getInstance()->getLogger()->info("§e$counts §fCommand(s) unloaded");
        }
        
        $Commandes = [
            "SolariaStaff.Kick" => new KickCommand(),
            "SolariaPlayer.Spawn" => new SpawnCommand(),
            "SolariaPlayer.MSG" => new MsgCommand(),
            "SolariaPlayer.NightVision" => new NightVisionCommand(),
            "SolariaPlayer.Kit" => new KitCommand(),
            "SolariaPlayer.Report" => new ReportCommand(),
            "SolariaStaff.Ban" => new BanCommand(),
            "SolariaStaff.BanList" => new BanListCommand(),
            "SolariaStaff.FloatingText" => new FloatingTextCommand(),
            "SolariaStaff.AddMoney" => new AddMoneyCommand(),
            "SolariaPlayer.MyMoney" => new MyMoneyCommand(),
            "SolariaStaff.SpawnBox" => new BoxCommand(),
            "SolaraiaPlayer.Boutique" => new BoutiqueCommand(),
            "SolariaStaff.AddPb" => new AddPbCommand(),
            "SolariaPlayer.MyPb" => new MyPbCommand(),
            "SolariaStaff.GiveKey" => new GiveKeyCommand(),
            "SolariaGrade.Repair" => new RepairCommand(),
           // "SolariaGrade.Ec" => new EcCommand(),
            "SolariaPlayer.Purif" => new PurifCommand(),
            "SolariaPlayer.Event" => new EventCommand(),
            //"SolariaStaff.ForceKoth" => new ForceKothCommand(),
            "SolariaPlayer.Vote" => new VoteCommand(),
            "SolariaPlayer.Shop" => new ShopCommand(),
            "SolariaPlayer.Minage" => new MinageCommand(),
            "SolariaPlayer.Pay" => new PayCommand(),
            "SolariaPlayer.TopMoney" => new TopMoneyCommand(),
            "SolariaStaff.Mute" => new MuteCommand(),
            "SolariaStaff.Unmute" => new UnmuteCommand(),
            "SolariaStaff.Unban" => new UnbanCommand(),
            "SolariaPlayer.Capes" => new CapesCommand(),
            "SolariaPlayer.Particle" => new ParticuleCommand(),
            "SolariaPlayer.Atout" => new AtoutCommand(),
            "SolariaStaff.ForceClear" => new ForceClearCommand(),
            "SolariaPlayer.MineVip" => new MineVipCommand(),
            "SolariaStaff.Redem" => new RedemCommand()
            ];
        foreach($Commandes as $com => $cmd){
            Core::getInstance()->getServer()->getCommandMap()->register("Solaria", $cmd);
        }

        $count = count($Commandes);
        Core::getInstance()->getLogger()->info("§e$count §fCommand(s) load");
    }
    
    public static function registerEntities(){
        EntityFactory::getInstance()->register(FloatingText::class, function(World $world, CompoundTag $nbt) : FloatingText{
            return new FloatingText(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['floatingtext', 'minecraft:solaria_floatingtext']);
        
        EntityFactory::getInstance()->register(VoteBox::class, function(World $world, CompoundTag $nbt) : VoteBox{
            return new VoteBox(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ['vote_box', 'minecraft:vote_box']);
        
        EntityFactory::getInstance()->register(SaphirBox::class, function(World $world, CompoundTag $nbt) : SaphirBox{
            return new SaphirBox(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ['saphir_box', 'minecraft:saphir_box']);

        EntityFactory::getInstance()->register(SEnderPearl::class, function(World $world, CompoundTag $nbt) : SEnderPearl{
            return new SEnderPearl(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        }, ['ThrownEnderpearl', 'minecraft:ender_pearl'], EntityLegacyIds::ENDER_PEARL);

        EntityFactory::getInstance()->register(EggEntity::class, function(World $world, CompoundTag $nbt) : EggEntity{
            return new EggEntity(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        }, ['Egg', 'minecraft:egg'], EntityLegacyIds::EGG);

        EntityFactory::getInstance()->register(JadeBox::class, function(World $world, CompoundTag $nbt) : JadeBox{
            return new JadeBox(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['jade_box', 'minecraft:solaria_jadebox']);

        EntityFactory::getInstance()->register(RareBox::class, function(World $world, CompoundTag $nbt) : RareBox{
            return new RareBox(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['rare_box', 'minecraft:solaria_rarebox']);

        EntityFactory::getInstance()->register(UltimeBox::class, function(World $world, CompoundTag $nbt) : UltimeBox{
            return new UltimeBox(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['ultime_box', 'minecraft:solaria_ultimebox']);
        
        Core::getInstance()->getLogger()->info("§e4 §fEntitie(s) load");
    }
    
    public static function registerItems(){
        $array = [
            new MercureSword(),
            new SaphirSword(),
            new JadeSword(),
            new JadeHelmet(),
            new JadeChestplate(),
            new JadeLeggings(),
            new JadeBoots(),
            new SaphirHelmet(),
            new SaphirChestplate(),
            new SaphirLeggings(),
            new SaphirBoots(),
            new EnderItem(new ItemIdentifier(ItemIds::ENDER_PEARL, 0), "Solaria Ender Pearl"),
            new Egg(new ItemIdentifier(ItemIds::EGG, 0), "Solaria Fake Pearl")
        ];

        foreach($array as $item){
            ItemFactory::getInstance()->register($item, true);
            CreativeInventory::getInstance()->add($item);
        }

        $count = count($array);
        Core::getInstance()->getLogger()->info("§e$count §fItem(s) load");
    }

    public static function registerBlocks(){
        $array = [
            new Opaque(new BlockIdentifier(BlockLegacyIds::WOODEN_PRESSURE_PLATE, 0), "Wooden pressure plate", new BlockBreakInfo(0.5, BlockToolType::AXE))
        ];

        foreach($array as $block){
            BlockFactory::getInstance()->register($block, true);
        }

        $count = count($array);
        Core::getInstance()->getLogger()->info("§e$count §fBlock(s) load");
    }

}