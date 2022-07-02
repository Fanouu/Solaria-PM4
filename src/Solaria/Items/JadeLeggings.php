<?php

namespace Solaria\Items;

use pocketmine\item\Armor;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\inventory\ArmorInventory;

class JadeLeggings extends Armor {
    
    public function __construct(){
        parent::__construct(new ItemIdentifier(ItemIds::CHAIN_LEGGINGS, 0), "Jade Leggings", new ArmorTypeInfo(6, 1095, ArmorInventory::SLOT_LEGS));
    }
    
    public function getBaseDurability(): int{
        return 225;
    }
    
    public function setDamage(int $damage, bool $bool = false) : Item{
		if($damage < 0 || $damage > $this->getMaxDurability()){
			throw new \InvalidArgumentException("Damage must be in range 0 - " . $this->getMaxDurability());
		}
		
		if($bool == true){
		  $this->getNamedTag()->setString("Durabilité", self::getMaxDurability());
		}
		$this->damage = $damage;
		return $this;
	}

    public function applyDamage(int $amount) : bool{
        $amount -= self::getUnbreakingDamageReductions($this, $amount);
        $baseDurability = $this->getBaseDurability();
        $newDurability = self::getMaxDurability();
        if(is_null($this->getNamedTag()->getTag("Durabilité"))) $this->getNamedTag()->setString("Durabilité", $newDurability-1);
        $durability = intval($this->getNamedTag()->getString("Durabilité"));
        $damage = $newDurability / $baseDurability;
        if($durability <= 0) {
            return parent::applyDamage($baseDurability);
        }
        $this->getNamedTag()->setString("Durabilité", $durability - $amount);
        $damage = intval(round($durability / $damage - $baseDurability) * -1);
        $this->setDamage($damage);
        $this->setLore(["§1§rDurabilité: §7" . $this->getNamedTag()->getString("Durabilité") . ""]);
        return true;
    }

    /**
     * @param Item $item
     * @param int $amount
     * @return int
     */
    protected static function getUnbreakingDamageReductions(Item $item, int $amount) : int {
        if (($unbreakingLevel = $item->getEnchantmentLevel(VanillaEnchantments::UNBREAKING())) > 0) {
            $negated = 0;
            $chance = 1 / ($unbreakingLevel + 1);
            for($i = 0; $i < $amount; ++$i) {
                if(mt_rand(1, 100) > 60 and lcg_value() > $chance){
                    $negated++;
                }
            }
            return $negated;
        }
        return 0;
    }
}