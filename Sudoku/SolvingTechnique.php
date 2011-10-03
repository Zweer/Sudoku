<?php

class Zwe_Sudoku_SolvingTechnique
{
    /**
     * @var string
     */
    private $_name;
    
    private static $_techniques = array('Hidden Single',
                                        'Direct Pointing',
                                        'Direct Hidden Pair',
                                        'Naked Single',
                                        'Direct Hidden Triplet',
                                        'Pointing & Claiming',
                                        'Naked Pair',
                                        'X-Wing',
                                        'Hidden Pair',
                                        'Naked Triplet',
                                        'Swordfish',
                                        'Hidden Triplet',
                                        'XY-Wing',
                                        'XYZ-Wing',
                                        'Unique Rectangle / Loop',
                                        'Naked Quad',
                                        'Jellyfish',
                                        'Hidden Quad',
                                        'Bivalue Universal Grave',
                                        'Aligned Pair Exclusion',
                                        'Forcing Chains & Cycles',
                                        'Aligned Triplet Exclusion',
                                        'Nishio Forcing Chains',
                                        'Multiple Forcing Chains',
                                        'Dynamic Forcing Chains',
                                        'Dynamic Forcing Chains (+)',
                                        'Nested Forcing Chains');
    
    public function __construct($Name)
    {
        if(in_array($Name, self::$_techniques))
            $this->_name = $Name;
    }
    
    public function __toString()
    {
        return $this->_name;
    }

    public static function getAll()
    {
        return self::$_techniques;
    }
}