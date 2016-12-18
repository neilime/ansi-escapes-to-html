<?php

namespace AnsiEscapesToHtml;

class Highlighter
{

    const CONTAINER_TAG = 'CONTAINER_TAG';
    const END_OF_LINE_TAG = 'END_OF_LINE_TAG';

    /**
     * Define tag for html conversion
     * @var array
     */
    protected $conversionTags = array(
        self::CONTAINER_TAG => 'span',
        self::END_OF_LINE_TAG => '<br/>',
    );

    /**
     * @var array
     */
    protected $definedStyles = array(
        // Format
        0 => 'font-weight:normal;text-decoration:none;color:White;background-color:Black', // Reset all styles
        1 => 'font-weight:bold', // Bold/bright
        2 => '', // Dim ? unknown style
        4 => 'text-decoration:underline', // Underlined
        5 => '', // Blink ? unknown style
        7 => '', // Inverted ? unknown style
        8 => 'display:none', // Hidden
        21 => 'font-weight:normal', // Reset bold/bright
        22 => '', // Reset dim ? unknown style
        24 => 'text-decoration:none', // Reset underlined
        25 => 'text-decoration:none', // Reset blink
        27 => '', // Reset reverse ? unknown style
        28 => 'display:inline-block', // Reset hidden
        // Foreground colors
        39 => 'color:White', // Default foreground color
        30 => 'color:Black', // Black
        31 => 'color:Red', // Red
        32 => 'color:Green', // Green
        33 => 'color:Yellow', // Yellow
        34 => 'color:Blue', // Blue
        35 => 'color:Magenta', // Magenta
        36 => 'color:Cyan', // Cyan
        37 => 'color:LightGray', // Light gray
        90 => 'color:DarkGray', // Dark gray
        91 => 'color:LightRed', // Light red
        92 => 'color:LightGreen', // Light green
        93 => 'color:LightYellow', // Light yellow
        94 => 'color:LightBlue', // Light blue
        95 => 'color:#F466CC', // Light magenta
        96 => 'color:LightCyan', // Light cyan
        97 => 'color:White', // White
        // Background colors
        49 => 'background-color:Black', // Default background color
        40 => 'background-color:Black', // Black
        41 => 'background-color:Red', // Red
        42 => 'background-color:Green', // Green
        43 => 'background-color:Yellow', // Yellow
        44 => 'background-color:Blue', // Blue
        45 => 'background-color:Magenta', // Magenta
        46 => 'background-color:Cyan', // Cyan
        47 => 'background-color:LightGray', // Light gray
        100 => 'background-color:DarkGray', // Dark gray
        101 => 'background-color:LightRed', // Light red
        102 => 'background-color:LightGreen', // Light green
        103 => 'background-color:LightYellow', // Light yellow
        104 => 'background-color:LightBlue', // Light blue
        105 => 'background-color:#F466CC', // Light magenta
        106 => 'background-color:LightCyan', // Light cyan
        107 => 'background-color:White', // White
    );

    /**
     * @var array
     */
    protected $rgbColors = array();

    /**
     * @param string $sSubject
     * @return string
     * @throws \InvalidArgumentException
     */
    public function toHtml($sSubject)
    {
        if (!is_string($sSubject)) {
            throw new \InvalidArgumentException('Argument "$sSubject" expects a string, "' . (is_object($sSubject) ? get_class($sSubject) : gettype($sSubject)) . '" given');
        }

        // Convert ansi escapes
        $aStyles = $this->getDefaultStyles();

        // Start by a "default styles" container if no styles are expected at the begining
        if (!preg_match('/^(\e\[([0-9;]+)m)/', $sSubject)) {
            $sSubject = $this->openContainerWithStyles($aStyles) . $sSubject;
            $bContainerOpen = true;
        } else {
            $bContainerOpen = false;
        }

        $oSelf = $this;
        $sSubject = preg_replace_callback('/(\e\[([0-9;]+)m)/', function($aMatches)use($oSelf, &$aStyles, &$bContainerOpen) {
            $sReturn = '';
            if ($bContainerOpen) {
                $sReturn .= $oSelf->closeContainer();
                $bContainerOpen = false;
            }
            $aStyles = $oSelf->getCssStylesFromCode($aMatches[2], $aStyles);
            $sReturn .= $oSelf->openContainerWithStyles($aStyles);
            $bContainerOpen = true;
            return $sReturn;
        }, $sSubject);

        if ($bContainerOpen) {
            $sSubject .= $this->closeContainer();
            $bContainerOpen = false;
        }

        // Convert end of lines
        $sEndOfLineConversion = $this->getConversionTag(self::END_OF_LINE_TAG);
        if ($sEndOfLineConversion !== PHP_EOL) {
            $sSubject = str_replace(PHP_EOL, $sEndOfLineConversion, $sSubject);
        }

        return $sSubject;
    }

    /**
     * @param array $aStyles
     * @return type
     */
    public function openContainerWithStyles(array $aStyles)
    {
        $sStyles = '';
        foreach ($aStyles as $sStyle => $sValue) {
            $sStyles .= $sStyle . ':' . $sValue . ';';
        }
        return '<' . $this->getConversionTag(self::CONTAINER_TAG) . ' style="' . $sStyles . '">';
    }

    /**
     * @return string
     */
    public function closeContainer()
    {
        return '</' . $this->getConversionTag(self::CONTAINER_TAG) . '>';
    }

    /**
     * @return array
     */
    public function getDefaultStyles()
    {
        $aDefinedStyles = $this->getDefinedStyles();

        $aDefaultStyles = array();
        foreach (explode(';', $aDefinedStyles[0]) as $sStyle) {
            $aStyleParts = explode(':', $sStyle);
            $aDefaultStyles[$aStyleParts[0]] = $aStyleParts[1];
        }
        return $aDefaultStyles;
    }

    /**
     * Define an array of css styles for a given code and default styles
     * @param string $sCode
     * @param array $aStyles
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getCssStylesFromCode($sCode, array $aStyles)
    {
        if (!is_string($sCode)) {
            throw new \InvalidArgumentException('Argument "$sCode" expects a string, "' . (is_object($sCode) ? get_class($sCode) : gettype($sCode)) . '" given');
        }
        $sCode = trim($sCode);

        // 88/256 Colors
        $aColorsMatches = null;
        if (preg_match('/(38|48);5;([0-9]+)/', $sCode, $aColorsMatches)) {
            $iColorNumber = (int) $aColorsMatches[2];

            // Foreground color
            if ((int) $aColorsMatches[1] === 38) {
                $aStyles['color'] = $this->getRgbColor($iColorNumber);
            }
            // Background color
            else {
                $aStyles['background-color'] = $this->getRgbColor($iColorNumber);
            }
            $sCode = trim(str_replace($aColorsMatches[0], '', $sCode));
        }
        if ($sCode !== '') {
            // Defined styles codes
            $aDefinedStyles = $this->getDefinedStyles();
            foreach (explode(';', $sCode) as $sCodePart) {
                $sCodePart = trim($sCodePart);
                if ($sCodePart === '') {
                    continue;
                }
                $iCode = (int) $sCodePart;
                if (isset($aDefinedStyles[$iCode])) {
                    if ($aDefinedStyles[$iCode]) {
                        foreach (explode(';', $aDefinedStyles[$iCode]) as $sStyle) {
                            $aStylesPart = explode(':', $sStyle);
                            $aStyles[$aStylesPart[0]] = $aStylesPart[1];
                        }
                    }
                }
            }
        }
        return $aStyles;
    }

    /**
     * @param string $sTagName
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getConversionTag($sTagName)
    {
        if (!is_string($sTagName)) {
            throw new \InvalidArgumentException('Argument "$sTagName" expects a string, "' . (is_object($sTagName) ? get_class($sTagName) : gettype($sTagName)) . '" given');
        }
        if (!isset($this->conversionTags[$sTagName])) {
            throw new \InvalidArgumentException('Argument "$sTagName" "' . $sTagName . '" is not an existing tag');
        }
        return $this->conversionTags[$sTagName];
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getDefinedStyles()
    {
        if (is_array($this->definedStyles)) {
            return $this->definedStyles;
        }
        throw new \LogicException('Property "definedStyles" expects an array, "' . (is_object($this->definedStyles) ? get_class($this->definedStyles) : gettype($this->definedStyles)) . '" defined');
    }

    /**
     * @param array $aDefinedStyles
     * @return \AnsiEscapesToHtml\Highlighter
     */
    public function setDefinedStyles(array $aDefinedStyles)
    {
        $this->definedStyles = $aDefinedStyles;
        return $this;
    }

    /**
     * @param integer $iColorNumber
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getRgbColor($iColorNumber)
    {
        if (!is_integer($iColorNumber)) {
            throw new \InvalidArgumentException('Argument "$iColorNumber" expects an integer, "' . (is_object($iColorNumber) ? get_class($iColorNumber) : gettype($iColorNumber)) . '" given');
        }

        // Generate rgb colors array
        if (!$this->rgbColors) {
            for ($iRedIterator = 0; $iRedIterator < 6; $iRedIterator++) {
                for ($iGreenIterator = 0; $iGreenIterator < 6; $iGreenIterator++) {
                    for ($iBlueIterator = 0; $iBlueIterator < 6; $iBlueIterator++) {
                        $iKeyColorNumer = 16 + ($iRedIterator * 36) + ($iGreenIterator * 6) + $iBlueIterator;
                        $sRgb = 'rgb(' . ($iRedIterator ? $iRedIterator * 40 + 55 : 0) . ',' . ($iGreenIterator ? $iGreenIterator * 40 + 55 : 0) . ',' . ($iBlueIterator ? $iBlueIterator * 40 + 55 : 0) . ')';
                        $this->rgbColors[$iKeyColorNumer] = $sRgb;
                    }
                }
            }
            // Gray scale
            for ($iGrayIterator = 0; $iGrayIterator < 24; $iGrayIterator++) {
                $iKeyColorNumer = $iGrayIterator + 232;
                $iGray = $iGrayIterator + 10 + 8;
                $this->rgbColors[$iKeyColorNumer] = 'rgb(' . $iGray . $iGray . $iGray . ')';
            }
        }

        if (isset($this->rgbColors[$iColorNumber])) {
            return $this->rgbColors[$iColorNumber];
        }
        throw new \InvalidArgumentException('Argument "$iColorNumber" "' . $iColorNumber . '" expects to be in range 16,256');
    }
}
