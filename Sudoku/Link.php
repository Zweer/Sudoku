<?php

class Zwe_Sudoku_Link
{
    /**
     * @var Zwe_Sudoku_Cell
     */
    private $_srcCell;
    /**
     * @var int
     */
    private $_srcValue;
    /**
     * @var Zwe_Sudoku_Cell
     */
    private $_dstCell;
    /**
     * @var int
     */
    private $_dstValue;

    /**
     * @param Zwe_Sudoku_Cell $SrcCell
     * @param int $SrcValue
     * @param Zwe_Sudoku_Cell $DstCell
     * @param int $DstValue
     */
    public function __construct(Zwe_Sudoku_Cell $SrcCell, $SrcValue, Zwe_Sudoku_Cell $DstCell, $DstValue)
    {
        $this->_srcCell = $SrcCell;
        if(is_int($SrcValue))
            $this->_srcValue = $SrcValue;
        else
            trigger_error("'$SrcValue' is not a valid value for 'srcValue': 'int' expected", E_USER_ERROR);

        $this->_dstCell = $DstCell;
        if(is_int($DstValue))
            $this->_dstValue = $DstValue;
        else
            trigger_error("'$DstValue' is not a valid value for 'dstValue': 'int' expected", E_USER_ERROR);
    }

    /**
     * @return Zwe_Sudoku_Cell
     */
    public function getSrcCell()
    {
        return $this->_srcCell;
    }

    /**
     * @return int
     */
    public function getSrcValue()
    {
        return $this->_srcValue;
    }

    /**
     * @return Zwe_Sudoku_Cell
     */
    public function getDstCell()
    {
        return $this->_dstCell;
    }

    /**
     * @return int
     */
    public function getDstValue()
    {
        return $this->_dstValue;
    }
}