<?php

class Zwe_Sudoku_Cell
{
    /**
     * @var Zwe_Sudoku_Grid
     */
    private $_grid;
    /**
     * @var int
     */
    private $_x;
    /**
     * @var int
     */
    private $_y;
    /**
     * @var int
     */
    private $_value = 0;
    /**
     * @var array
     */
    private $_potentialValues = array();

    public function __construct(Zwe_Sudoku_Grid $Grid, $X, $Y)
    {
        $this->_grid = $Grid;

        if(is_int($X) && $X < 9)
            $this->_x = $X;
        else
            trigger_error("'$X' can't be used as the X of a cell: expected 'int'", E_USER_ERROR);

        if(is_int($Y) && $Y < 9)
            $this->_y = $Y;
        else
            trigger_error("'$Y' can't be used as the Y of a cell: expected 'int'", E_USER_ERROR);
    }

    public function getX()
    {
        return $this->_x;
    }

    public function getY()
    {
        return $this->_y;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function isEmpty()
    {
        return $this->getValue() == 0;
    }

    public function setValue($Value)
    {
        if(is_int($Value) && $Value <= 9)
            $this->_value = $Value;
        else
            trigger_error("'$Value' can't be used as the value of a cell: expected 'int'", E_USER_ERROR);
    }

    public function setValueAndCancel($Value)
    {
        if($Value == 0 || !$this->isEmpty())
            trigger_error("The value of the cell cannot be set: it's already set", E_USER_ERROR);

        $this->setValue($Value);
        $this->_potentialValues = array();

        foreach(Zwe_Sudoku_Grid::getRegionTypes() as $RegionType)
        {
            $Region = $this->_grid->getRegionAt($RegionType, $this->_x, $this->_y);
            for($i = 0; $i < 9; ++$i)
            {
                $Region->getCell($i)->removePotentialValue($Value);
            }
        }
    }

    public function getPotentialValues()
    {
        return $this->_potentialValues;
    }

    public function hasPotentialValue($Value)
    {
        return in_array($Value, $this->_potentialValues);
    }

    public function addPotentialValue($Value)
    {
        $this->_potentialValues[] = $Value;
    }

    public function removePotentialValue($Value)
    {
        if(in_array($Value, $this->_potentialValues))
            unset($this->_potentialValues[array_search($Value, $this->_potentialValues)]);
    }

    public function setPotentialValues(array $PotentialValues)
    {
        $this->_potentialValues = $PotentialValues;
    }

    public function clearPotentialValues()
    {
        $this->_potentialValues = array();
    }

    public function getHouseCells()
    {
        $Res = array();

        foreach(Zwe_Sudoku_Grid::getRegionTypes() as $RegionType)
        {
            $Region = $this->_grid->getRegionAt($RegionType, $this->_x, $this->_y);
            for($i = 0; $i < 9; ++$i)
            {
                $Res[] = $Region->getCell($i);
            }
        }

        unset($Res[array_search($this, $Res)]);
        return array_unique($Res);
    }

    private static function toString($X, $Y)
    {
        if(Zwe_Sudoku_Settings::getInstance()->isRCNotation())
            return "R" . ($Y + 1) . "C" . ($X + 1);
        else
            return (string)('A' + $X) . ($Y + 1);
    }

    public function toFullString()
    {
        return "Cell " . self::toString($this->_x, $this->_y);
    }

    public function __toString()
    {
        return self::toString($this->_x, $this->_y);
    }

    public static function toFullStrings(array $Cells)
    {
        $Ret  = 'Cell';
        if(count($Cells) <= 1)
            $Ret .= ' ';
        else
            $Ret .= 's ';

        $Ret .= self::toStrings($Cells);

        return $Ret;
    }

    public static function toStrings(array $Cells)
    {
        $Ret = '';
        foreach($Cells as $Cell)
        {
            $Ret .= self::toString($Cell->getX(), $Cell->getY()) . ', ';
        }

        if(count($Cells) > 0)
            $Ret = substr($Ret, 0, -2);

        return $Ret;
    }

    public function copyTo(Zwe_Sudoku_Cell $Other)
    {
        if($Other->getX() != $this->_x || $Other->getY() != $this->_y)
            trigger_error("Cell cannot be copied. Coords aren't the same", E_USER_ERROR);

        $Other->setValue($this->getValue());
        $Other->setPotentialValues($this->getPotentialValues());
    }
}