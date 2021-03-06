<?php
    
namespace Solaria\Items;

use pocketmine\item\Sword;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\enchantment\VanillaEnchantments;

class MercureSword extends Sword{
    
    public function __construct(){
        parent::__construct(new ItemIdentifier(743, 0), "Mercure Sword", ToolTier::NETHERITE());
    }
    
    public function getBlockToolType () : int{
        return BlockToolType::SWORD;
    }
    
    public function setDamage(int $damage, bool $bool = false) : Item{
		if($damage < 0 || $damage > $this->getMaxDurability()){
			throw new \InvalidArgumentException("Damage must be in range 0 - " . $this->getMaxDurability());
		}
		
		if($bool == true){
		  $this->getNamedTag()->setString("Durabilit√©", self::getMaxDurability());
		}
		$this->damage = $damage;
		return $this;
	}

    public function getAttackPoints() : int{
        return 8;
    }

    public function getBlockToolHarvestLevel() : int{
        return 1;
    }

    public function getMiningEfficiency(bool $isCorrectTool) : float{
        return parent::getMiningEfficiency($isCorrectTool) * 1.5;
    }

    protected function getBaseMiningEfficiency() : float{
        return 10;
    }

    public function onDestroyBlock(Block $block) : bool{
		if(!$block->getBreakInfo()->breaksInstantly()){
			return $this->applyDamage(2);
		}
		return false;
	}

    public function onAttackEntity(Entity $victim) : bool{
        return $this->applyDamage(1);
    }

    public function getMaxDurability() : int{
        return 4561;
    }

    public function getBaseDurability(): int {
       return 2031;
    }

    public function applyDamage(int $amount) : bool{
        $amount -= self::getUnbreakingDamageReductions($this, $amount);
        $baseDurability = $this->getBaseDurability();
        $newDurability = self::getMaxDurability();
        if(is_null($this->getNamedTag()->getTag("Durabilit√©"))) $this->getNamedTag()->setString("Durabilit√©", $newDurability-1);
        $durability = intval($this->getNamedTag()->getString("Durabilit√©"));
        $damage = $newDurability / $baseDurability;
        if($durability <= 0) {
            $this->setCount($this->getCount()-1);
        }
        $this->getNamedTag()->setString("Durabilit√©", $durability - $amount);
        $damage = intval(round($durability / $damage - $baseDurability) * -1);
        $this->setDamage($damage);
        $this->setLore(["¬ß1¬ßrDurabilit√©: ¬ß7" . $this->getNamedTag()->getString("Durabilit√©") . ""]);
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