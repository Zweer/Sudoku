<?php

require_once(dirname(__FILE__) . '/../Region.php');

class Zwe_Sudoku_Region_Block extends Zwe_Sudoku_Region
{
    /**
     * @var int
     */
    private $_vNum, $_hNum;

    /**
     * @param Zwe_Sudoku_Grid $Grid
     * @param int $VNum
     * @param int $HNum
     */
    public function __construct(Zwe_Sudoku_Grid $Grid, $VNum, $HNum)
    {
        parent::__construct($Grid);

        if(is_int($VNum) && $VNum < 3)
            $this->_vNum = $VNum;
        else
            trigger_error("'$VNum' cannot be a value for 'vNum': expected 'int'", E_USER_ERROR);

        if(is_int($HNum) && $HNum < 3)
            $this->_hNum = $HNum;
        else
            trigger_error("'$HNum' cannot be a value for 'hNum': expected 'int'", E_USER_ERROR);
    }

    /**
     * @return int
     */
    public function getVIndex()
    {
        return $this->_vNum;
    }

    /**
     * @return int
     */
    public function getHIndex()
    {
        return $this->_hNum;
    }

    /**
     * @param $index
     * @return Zwe_Sudoku_Cell
     */
    public function getCell($index)
    {
        return $this->_grid->getCell($this->_hNum * 3 + $index % 3, $this->_vNum * 3 + $index % 3);
    }

    /**
     * @param Zwe_Sudoku_Cell $Cell
     * @return int
     */
    public function indexOf(Zwe_Sudoku_Cell $Cell)
    {
        return ($Cell->getY() % 3) * 3 + $Cell->getX() % 3;
    }

    /**
     * @param Zwe_Sudoku_Region $OtherRegion
     * @return bool
     */
    public function crosses(Zwe_Sudoku_Region $OtherRegion)
    {
        if($OtherRegion instanceof Zwe_Sudoku_Region_Block)
            return $this->_vNum == $OtherRegion->getVIndex() && $this->_hNum == $OtherRegion->getHIndex();
        elseif($OtherRegion instanceof Zwe_Sudoku_Region_Column)
            return $OtherRegion->crosses($this);
        elseif($OtherRegion instanceof Zwe_Sudoku_Region_Row)
            return $OtherRegion->crosses($this);
        else
            return parent::crosses($OtherRegion);
    }

    public function __toString()
    {
        return 'block';
    }

    public function toFullString()
    {
        return $this . ' ' . ($this->_vNum * 3 + $this->_hNum + 1);
    }
}