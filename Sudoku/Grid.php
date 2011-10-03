<?php

require_once(dirname(__FILE__) . '/Cell.php');
require_once(dirname(__FILE__) . '/Region/Row.php');
require_once(dirname(__FILE__) . '/Region/Column.php');
require_once(dirname(__FILE__) . '/Region/Block.php');
require_once(dirname(__FILE__) . '/Settings.php');
require_once(dirname(__FILE__) . '/SolvingTechnique.php');

class Zwe_Sudoku_Grid
{
    private static $_regionTypes = null;

    private $_cells = array();

    private $_rows = array();
    private $_columns = array();
    private $_blocks = array();

    public function __construct()
    {
        for($y = 0; $y < 9; ++$y)
        {
            $this->_cells[$y] = array();
            for($x = 0; $x < 9; ++$x)
            {

                $this->_cells[$y][$x] = new Zwe_Sudoku_Cell($this, $x, $y);
            }
        }

        for($i = 0; $i < 9; ++$i)
        {
            $this->_rows[$i] = new Zwe_Sudoku_Region_Row($this, $i);
            $this->_columns[$i] = new Zwe_Sudoku_Region_Column($this, $i);
            $this->_blocks[$i] = new Zwe_Sudoku_Region_Block($this, $i);
        }
    }

    /**
     * @param int $X
     * @param int $Y
     * @return Zwe_Sudoku_Cell
     */
    public function getCell($X, $Y)
    {
        return $this->_cells[$Y][$X];
    }

    /**
     * @param string $RegionType
     * @return array
     */
    public function getRegions($RegionType)
    {
        switch(strtolower($RegionType))
        {
            case 'row':
            case 'rows':
                return $this->_rows;
            break;

            case 'column':
            case 'columns':
                return $this->_columns;
            break;

            case 'block':
            case 'blocks':
                return $this->_blocks;
            break;
        }

        return null;
    }

    /**
     * @param int $i
     * @return Zwe_Sudoku_Region_Row
     */
    public function getRow($i)
    {
        return $this->_rows[$i];
    }

    /**
     * @param int $i
     * @return Zwe_Sudoku_Region_Column
     */
    public function getColumn($i)
    {
        return $this->_columns[$i];
    }

    /**
     * @param int $Y
     * @param int $X
     * @return Zwe_Sudoku_Region_Block
     */
    public function getBlock($Y, $X = null)
    {
        if(isset($X))
            return $this->_blocks[$Y * 3 + $X];
        else
            return $this->_blocks[$Y];
    }

    public function setCellValue($X, $Y, $Value)
    {
        $this->_cells[$Y][$X]->setValue($Value);
    }

    /**
     * @param int $X
     * @param int $Y
     * @return int
     */
    public function getCellValue($X, $Y)
    {
        return $this->_cells[$Y][$X]->getValue();
    }

    /**
     * @param int $X
     * @param int $Y
     * @return Zwe_Sudoku_Region_Row
     */
    public function getRowAt($X, $Y)
    {
        return $this->_rows[$Y];
    }

    /**
     * @param int $X
     * @param int $Y
     * @return Zwe_Sudoku_Region_Column
     */
    public function getColumnAt($X, $Y)
    {
        return $this->_columns[$X];
    }

    /**
     * @param int $X
     * @param int $Y
     * @return Zwe_Sudoku_Region_Block
     */
    public function getBlockAt($X, $Y)
    {
        return $this->_blocks[intval($Y / 3) * 3 + intval($X / 3)];
    }

    /**
     * @param string $RegionType
     * @param int|Zwe_Sudoku_Cell $X
     * @param int $Y
     * @return Zwe_Sudoku_Region
     */
    public function getRegionAt($RegionType, $X, $Y = null)
    {
        if(!isset($Y))
        {
            $Y = $X->getY();
            $X = $X->getX();
        }

        switch(strtolower($RegionType))
        {
            case 'row':
            case 'rows':
                return $this->getRowAt($X, $Y);
            break;

            case 'column':
            case 'columns':
                return $this->getColumnAt($X, $Y);
            break;

            case 'block':
            case 'blocks':
                return $this->getBlockAt($X, $Y);
            break;
        }

        return null;
    }

    /**
     * Get the first cell that cancels the given cell.
     * More precisely, get the first cell that:
     * - is in the same row, column or block of the given cell;
     * - contains the given value.
     * The order used for the "first" is not defined, but is garanteed to be consistent across multiple invications.
     *
     * @param Zwe_Sudoku_Cell $Target
     * @param int $Value
     * @return Zwe_Sudoku_Cell|null
     */
    public function getFirstCancellerOf(Zwe_Sudoku_Cell $Target, $Value)
    {
        foreach(self::getRegionTypes() as $RegionType)
        {
            $Region = $this->getRegionAt($RegionType, $Target->getX(), $Target->getY());
            for($i = 0; $i < 9; ++$i)
            {
                $Cell = $Region->getCell($i);
                if($Cell != $Target && $Cell->getValue() == $Value)
                    return $Cell;
            }
        }

        return null;
    }

    public function copyTo(Zwe_Sudoku_Grid $Other)
    {
        for($y = 0; $y < 9; ++$y)
            for($x = 0; $x < 9; ++$x)
                $this->_cells[$y][$x]->copyTo($Other->getCell($x, $y));
    }

    /**
     * Get the number of occurrences of a given value in this grid.
     *
     * @param int $Value
     * @return int
     */
    public function getCountOccurrencesOfValue($Value)
    {
        $Ret = 0;

        for($y = 0; $y < 9; ++$y)
            for($x = 0; $x < 9; ++$x)
                if($this->_cells[$y][$x]->getValue() == $Value)
                    $Ret++;

        return $Ret;
    }

    public function __toString()
    {
        $Ret = '';

        for($y = 0; $y < 9; ++$y)
        {
            for($x = 0; $x < 9; ++$x)
            {
                $Value = $this->getCellValue($x, $y);
                if(0 == $Value)
                    $Ret .= '.';
                else
                    $Ret .= $Value;
            }

            $Ret .= "\n";
        }

        return $Ret;
    }

    public function equals(object $Other)
    {
        if(!$Other instanceof Zwe_Sudoku_Grid)
            return false;

        for($y = 0; $y < 9; ++$y)
            for($x = 0; $x < 9; ++$x)
            {
                $ThisCell = $this->getCell($x, $y);
                $OtherCell = $Other->getCell($x, $y);
                if($ThisCell->getValue() != $OtherCell->getValue())
                    return false;
                if($ThisCell->getPotentialValues() != $OtherCell->getPotentialValues())
                    return false;
            }

        return true;
    }

    public function hashCode()
    {
        $Ret = "";
        for($y = 0; $y < 9; ++$y)
            for($x = 0; $x < 9; ++$x)
            {
                $Cell = $this->getCell($x, $y);
                $Ret .= $Cell->getValue();
                $Ret .= implode(',', $Cell->getPotentialValues());
            }

        return sha1($Ret);
    }

    /**
     * @static
     * @return array
     */
    public static function getRegionTypes()
    {
        if(!isset(self::$_regionTypes))
            self::$_regionTypes = array('row', 'column', 'block');

        return self::$_regionTypes;
    }
}