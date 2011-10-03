<?php

require_once(dirname(__FILE__) . '/../Region.php');

class Zwe_Sudoku_Region_Row extends Zwe_Sudoku_Region
{
    /**
     * @var int
     */
    private $_rowNum;

    /**
     * @param Zwe_Sudoku_Grid $Grid
     * @param int $Index
     */
    public function __construct(Zwe_Sudoku_Grid $Grid, $Index)
    {
        parent::__construct($Grid);

        if(is_int($Index) && $Index < 9)
            $this->_rowNum = $Index;
        else
            trigger_error("'$Index' cannot be a value for 'rowNum': expected 'int'", E_USER_ERROR);
    }

    /**
     * @return int
     */
    public function getRowNum()
    {
        return $this->_rowNum;
    }

    /**
     * @param $index
     * @return Zwe_Sudoku_Cell
     */
    public function getCell($index)
    {
        return $this->_grid->getCell($index, $this->_rowNum);
    }

    /**
     * @param Zwe_Sudoku_Cell $Cell
     * @return int
     */
    public function indexOf(Zwe_Sudoku_Cell $Cell)
    {
        return $Cell->getX();
    }

    /**
     * @param Zwe_Sudoku_Region $OtherRegion
     * @return bool
     */
    public function crosses(Zwe_Sudoku_Region $OtherRegion)
    {
        if($OtherRegion instanceof Zwe_Sudoku_Region_Block)
            return intval($this->_rowNum / 3) == $OtherRegion->getVNum();
        elseif($OtherRegion instanceof Zwe_Sudoku_Region_Column)
            return true;
        elseif($OtherRegion instanceof Zwe_Sudoku_Region_Row)
            return $this->_rowNum == $OtherRegion->getRowNum();
        else
            return parent::crosses($OtherRegion);
    }

    public function __toString()
    {
        return 'row';
    }

    public function toFullString()
    {
        if(Zwe_Sudoku_Settings::getInstance()->isRCNotation())
            return $this . ' R' . ($this->_rowNum + 1);
        else
            return $this . ' ' . ($this->_rowNum + 1);
    }
}