<?php

class Zwe_Sudoku_Settings
{
    const VERSION = 1;
    const REVISION = 1;
    const SUBREV = ".1";

    /**
     * @var Zwe_Sudoku_Settings
     */
    private static $_instance = null;

    /**
     * @var bool
     */
    private $_isRCNotation = false;
    /**
     * // TODO: useful or only for the gui?
     * @var bool
     */
    private $_isAntialiasing = true;
    /**
     * @var bool
     */
    private $_isShowingCandidates = true;
    /**
     * @var string
     */
    private $_lookAndFeelClassName = null;

    /**
     * @var array
     */
    private $_techniques = null;

    public function __construct()
    {
        $this->_techniques = Zwe_Sudoku_SolvingTechnique::getAll();
    }

    public function setRCNotation($IsRCNotation)
    {
        $this->_isRCNotation = !!$IsRCNotation;
    }

    public function isRCNotation()
    {
        return $this->_isRCNotation;
    }

    public function setAntialiasing($IsAntialiasing)
    {
        $this->_isAntialiasing = !!$IsAntialiasing;
    }

    public function isAntialiasing()
    {
        return $this->_isAntialiasing;
    }

    public function setShowingCandidates($IsShowingCandidates)
    {
        $this->_isShowingCandidates = !!$IsShowingCandidates;
    }

    public function isShowingCandidates()
    {
        return $this->_isShowingCandidates;
    }

    public function setLookAndFeelClassName($LookAndFeelClassName)
    {
        $this->_lookAndFeelClassName = $LookAndFeelClassName;
    }

    public function getLookAndFeelClassName()
    {
        return $this->_lookAndFeelClassName;
    }

    public function setTechniques(array $Techniques)
    {
        $this->_techniques = $Techniques;
    }

    public function addTechnique(Zwe_Sudoku_SolvingTechnique $Technique)
    {
        if(!in_array($Technique, $this->_techniques))
            $this->_techniques[] = $Technique;
    }

    public function removeTechnique(Zwe_Sudoku_SolvingTechnique $Technique)
    {
        if(in_array($Technique, $this->_techniques))
            unset($this->_techniques[array_search($Technique, $this->_techniques)]);
    }

    public function getTechniques()
    {
        return $this->_techniques;
    }

    public function isUsingAllTechniques()
    {
        return count($this->_techniques) == count(Zwe_Sudoku_SolvingTechnique::getAll());
    }

    public function isUsingOneOf(array $Techniques)
    {
        foreach($Techniques as $Technique)
        {
            if(in_array($Technique, $this->_techniques))
                return true;
        }

        return false;
    }

    public function isUsingAll(array $Techniques)
    {
        foreach($Techniques as $Technique)
        {
            if(!in_array($Technique, $this->_techniques))
                return false;
        }

        return true;
    }

    public function isUsingAllButMaybeNot(array $Techniques)
    {
        foreach(Zwe_Sudoku_SolvingTechnique::getAll() as $Technique)
        {
            if(!in_array($Technique, $this->_techniques) && !in_array($Technique, $Techniques))
                return false;
        }

        return true;
    }

    public static function getInstance()
    {
        if(!isset(self::$_instance))
            self::$_instance = new self();

        return self::$_instance;
    }
}