<?php

class Zwe_Sudoku_Solver
{
    /**
     * @var Zwe_Sudoku_Grid
     */
    private $_grid;

    private $_directHintProducer = array();
    private $_indirectHintProducers = array();
    private $_validatorHintProducers = array();
    private $_warningHintProducers = array();
    private $_chainingHintProducers = array();
    private $_chainingHintProducers2 = array();
    private $_advancedHintProducers = array();
    private $_experimentalHintProducers = array();

    private $_isUsingAdvanced = false;

    public function __construct(Zwe_Sudoku_Grid $Grid)
    {
        $this->_grid = $Grid;

        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::HiddenSingle, $this->_directHintProducer, new Zwe_Sudoku_SolvingTechnique_HiddenSingle());
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::DirectPointing, $this->_directHintProducer, new Zwe_Sudoku_SolvingTechnique_Locking(true));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::DirectHiddenPair, $this->_directHintProducer, new Zwe_Sudoku_SolvingTechnique_HiddenSet(2, true));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NakedSingle, $this->_directHintProducer, new Zwe_Sudoku_SolvingTechnique_NakedSingle());
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::DirectHiddenTriplet, $this->_directHintProducer, new Zwe_Sudoku_SolvingTechnique_HiddenSet(3, true));

        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::PointingClaiming, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_Locking(false));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NakedPair, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_NakedSet(2));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::XWing, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_Fisherman(2));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::HiddenPair, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_HiddenSet(2, false));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NakedTriplet, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_NackedSet(3));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::Swordfish, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_Fisherman(3));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::HiddenTriplet, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_HiddenSet(3, false));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::XYWing, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_XYWing(false));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::XYZWing, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_XYWing(true));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::UniqueLoop, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_UniqueLoops());
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NakedQuad, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_NakedSet(4));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::Jellyfish, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_Fisherman(4));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::HiddenQuad, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_HiddenSet(4, false));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::BivalueUniversalGrave, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_BivalueUniversalGrave());
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::AlignedPairExclusion, $this->_indirectHintProducers, new Zwe_Sudoku_SolvingTechnique_AlignedPairExclusion());

        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::ForcingChainCycle, $this->_chainingHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(false, false, false, 0));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::AlignedTripletExclusion, $this->_chainingHintProducers, new Zwe_Sudoku_SolvingTechnique_AlignedExclusion(3));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NishioForcingChain, $this->_chainingHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(false, true, true, 0));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::MultipleForcingChain, $this->_chainingHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(true, false, false, 0));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::DynamicForcingChain, $this->_chainingHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(true, true, false, 0));

        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::DynamicForcingChainPlus, $this->_chainingHintProducers2, new Zwe_Sudoku_SolvingTechnique_Chaining(true, true, false, 1));

        // These rules are not really solving techniques. They check the validity of the puzzle.
        $this->_validatorHintProducers[] = new Zwe_Sudoku_SolvingTechnique_NoDoubles();

        $this->_warningHintProducers[] = new Zwe_Sudoku_SolvingTechnique_NumberOfFilledCells();
        $this->_warningHintProducers[] = new Zwe_Sudoku_SolvingTechnique_NumberOfValues();
        $this->_warningHintProducers[] = new Zwe_Sudoku_SolvingTechnique_BruteForceAnalysis(false);

        // These are very slow. We add them only as "rescue".
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NestedForcingChain, $this->_advancedHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(true, true, false, 2));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NestedForcingChain, $this->_advancedHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(true, true, false, 3));

        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NestedForcingChain, $this->_experimentalHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(true, true, false, 4));
        $this->addIfWorth(Zwe_Sudoku_SolvingTechnique::NestedForcingChain, $this->_experimentalHintProducers, new Zwe_Sudoku_SolvingTechnique_Chaining(true, true, false, 5));
    }

    private function addIfWorth($Technique, array &$Collection, Zwe_Sudoku_SolvingTechnique $Producer)
    {
        if(in_array($Technique, Zwe_Sudoku_Settings::getInstance()->getTechniques()) && !in_array($Producer, $Collection))
            $Collection[] = $Producer;
    }

    private function cancelBy($RegionType)
    {
        $Regions = $this->_grid->getRegions($RegionType);
        foreach($Regions as $Region)
        {
            for($i = 0; $i < 9; ++$i)
            {
                $Cell = $Region->getCell($i);
                if(!$Cell->isEmpty())
                {
                    $Value = $Cell->getValue();
                    for($j = 0; $j < 9; ++$j)
                        $Region->getCell($j)->removePotentialValue($Value);
                }
            }
        }
    }

    public function rebuildPotentialValues()
    {
        for($y = 0; $y < 9; ++$y)
            for($x = 0; $x < 9; ++$x)
            {
                $Cell = $this->_grid->getCell($x, $y);
                if($Cell->getValue() == 0)
                {
                    for($Value = 1; $Value <= 9; ++$Value)
                        $Cell->addPotentialValue($Value);
                }
            }

        $this->cancelPotentialValues();
    }

    public function cancelPotentialValues()
    {
        for($y = 0; $y < 9; ++$y)
            for($x = 0; $x < 9; ++$x)
            {
                $Cell = $this->_grid->getCell($x, $y);
                if($Cell->getValue() != 0)
                    $Cell->clearPotentialValues();
            }

        foreach(Zwe_Sudoku_Grid::getRegionTypes() as $RegionType)
            $this->cancelBy($RegionType);
    }
}