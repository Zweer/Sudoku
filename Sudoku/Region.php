<?php

abstract class Zwe_Sudoku_Region
{
    /**
     * @var Zwe_Sudoku_Grid
     */
    protected $_grid;

    public function __construct(Zwe_Sudoku_Grid $Grid)
    {
        $this->_grid = $Grid;
    }

    /**
     * @abstract
     * @param int $Index
     * @return Zwe_Sudoku_Cell
     */
    public abstract function getCell($Index);

    /**
     * @param Zwe_Sudoku_Cell $Cell
     * @return int
     */
    public function indexOf(Zwe_Sudoku_Cell $Cell)
    {
        for($i = 0; $i < 9; ++$i)
            if($this->getCell($i) == $Cell)
                return $i;

        return -1;
    }

    /**
     * @param int $Value
     * @return bool
     */
    public function contains($Value)
    {
        for($i = 0; $i < 9; ++$i)
            if($this->getCell($i)->getValue() == $Value)
                return true;

        return false;
    }

    /**
     * @param int $Value
     * @return array
     */
    public function getPotentialPositions($Value)
    {
        $Ret = array();
        for($i = 0; $i < 9; ++$i)
            $Ret[$i] = $this->getCell($i)->hasPotentialValue($Value);

        return $Ret;
    }

    /**
     * @see getPotentialPositions()
     * @param int $Value
     * @return array
     */
    public function copyPotentialPositions($Value)
    {
        return $this->getPotentialPositions($Value);
    }

    /**
     * @return array
     */
    public function getCellSet()
    {
        $Ret = array();
        for($i = 0; $i < 9; ++$i)
            $Ret[$i] = $this->getCell($i);

        return $Ret;
    }

    public function commonCells(Zwe_Sudoku_Region $OtherRegion)
    {
        $Cells = $this->getCellSet();
        $Ret = array();

        for($i = 0; $i < 9; ++$i)
        {
            $OtherCell = $OtherRegion->getCell($i);
            if(in_array($OtherCell, $Cells))
                $Ret[] = $OtherCell;
        }

        return $Ret;
    }

    public function crosses(Zwe_Sudoku_Region $OtherRegion)
    {
        $CommonCells = $this->commonCells($OtherRegion);

        return count($CommonCells) > 0;
    }

    public function getEmptyCellCount()
    {
        $Ret = 0;
        for($i = 0; $i < 9; ++$i)
            if($this->getCell($i)->isEmpty())
                $Ret++;

        return $Ret;
    }

    public abstract function __toString();
    public abstract function toFullString();
}