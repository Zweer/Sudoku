<?php

require_once(dirname(__FILE__) . '/../Region.php');

class Zwe_Sudoku_Region_Column extends Zwe_Sudoku_Region
{
    /**
     * @var int
     */
    private $_columnNum;

    /**
     * @param Zwe_Sudoku_Grid $Grid
     * @param int $Index
     */
    public function __construct(Zwe_Sudoku_Grid $Grid, $Index)
    {
        parent::__construct($Grid);

        if(is_int($Index) && $Index < 9)
            $this->_columnNum = $Index;
        else
            trigger_error("'$Index' cannot be a value for 'columnNum': expected 'int'", E_USER_ERROR);
    }

    /**
     * @return int
     */
    public function getColumnNum()
    {
        return $this->_columnNum;
    }

    /**
     * @param int $index
     * @return Zwe_Sudoku_Cell
     */
    public function getCell($index)
    {
        return $this->_grid->getCell($this->_columnNum, $index);
    }

    /**
     * @param Zwe_Sudoku_Cell $Cell
     * @return int
     */
    public function indexOf(Zwe_Sudoku_Cell $Cell)
    {
        return $Cell->getY();
    }

    /**
     * @param Zwe_Sudoku_Region $OtherRegion
     * @return bool
     */
    public function crosses(Zwe_Sudoku_Region $OtherRegion)
    {
        if($OtherRegion instanceof Zwe_Sudoku_Region_Block)
            return intval($this->_columnNum / 3) == $OtherRegion->getHNum();
        elseif($OtherRegion instanceof Zwe_Sudoku_Region_Column)
            return $this->_columnNum == $OtherRegion->getColumnNum();
        elseif($OtherRegion instanceof Zwe_Sudoku_Region_Row)
            return true;
        else
            return parent::crosses($OtherRegion);
    }

    public function __toString()
    {
        return 'column';
    }

    public function toFullString()
    {
        if(Zwe_Sudoku_Settings::getInstance()->isRCNotation())
            return $this . ' C' . ($this->_columnNum + 1);
        else
            return $this . ' ' . ('A' + $this->_columnNum);
    }
}