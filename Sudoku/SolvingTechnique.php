<?php

class Zwe_Sudoku_SolvingTechnique
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var array
     */
    private static $_techniques = null;

    const HiddenSingle = "Hidden Single";
    const DirectPointing = "Direct Pointing";
    const DirectHiddenPair = "Direct Hidden Pair";
    const NakedSingle = "Naked Single";
    const DirectHiddenTriplet = "Direct Hidden Triplet";
    const PointingClaiming = "Pointing & Claiming";
    const NakedPair = "Naked Pair";
    const XWing = "X-Wing";
    const HiddenPair = "Hidden Pair";
    const NakedTriplet = "Naked Triplet";
    const Swordfish = "Swordfish";
    const HiddenTriplet = "Hidden Triplet";
    const XYWing = "XY-Wing";
    const XYZWing = "XYZ-Wing";
    const UniqueLoop = "Unique Rectangle / Loop";
    const NakedQuad = "Naked Quad";
    const Jellyfish = "Jellyfish";
    const HiddenQuad = "Hidden Quad";
    const BivalueUniversalGrave = "Bivalue Universal Grave";
    const AlignedPairExclusion = "Aligned Pair Exclusion";
    const ForcingChainCycle = "Forcing Chains & Cycles";
    const AlignedTripletExclusion = "Aligned Triplet Exclusion";
    const NishioForcingChain = "Nishio Forcing Chains";
    const MultipleForcingChain = "Multiple Forcing Chains";
    const DynamicForcingChain = "Dynamic Forcing Chains";
    const DynamicForcingChainPlus = "Dynamic Forcing Chains (+)";
    const NestedForcingChain = "Nested Forcing Chains";
    
    public function __construct($Name)
    {
        if(in_array($Name, self::getAll()))
            $this->_name = $Name;
    }
    
    public function __toString()
    {
        return $this->_name;
    }

    public static function getAll()
    {
        if(!isset(self::$_techniques))
        {
            $Reflection = new ReflectionClass('Zwe_Sudoku_SolvingTechnique');
            self::$_techniques = $Reflection->getConstants();
        }

        return self::$_techniques;
    }
}