<?php

namespace AnsiEscapesToHtml;

class Highlighter
{
    public const CONTAINER_TAG = 'CONTAINER_TAG';
    public const END_OF_LINE_TAG = 'END_OF_LINE_TAG';

    /**
     * Define tag for html conversion
     * @var array
     */
    protected $conversionTags = [
        self::CONTAINER_TAG => 'span',
        self::END_OF_LINE_TAG => '<br/>',
    ];

    /**
     * @var array
     */
    protected $definedStyles = [
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
    ];

    /**
     * @var array
     */
    protected $rgbColors = [];

    /**
     * @param string $subject
     * @return string
     */
    public function toHtml(string $subject): string
    {
        // Convert ansi escapes
        $styles = $this->getDefaultStyles();

        // Start by a "default styles" container if no styles are expected at the begining
        if (!preg_match('/^(\e\[([0-9;]+)m)/', $subject)) {
            $subject = $this->openContainerWithStyles($styles) . $subject;
            $isContainerOpen = true;
        } else {
            $isContainerOpen = false;
        }

        $self = $this;
        $subject = preg_replace_callback(
            '/(\e\[([0-9;]+)m)/',
            function ($matches) use ($self, &$styles, &$isContainerOpen) {
                $return = '';
                if ($isContainerOpen) {
                    $return .= $self->closeContainer();
                    $isContainerOpen = false;
                }
                $styles = $self->getCssStylesFromCode($matches[2], $styles);
                $return .= $self->openContainerWithStyles($styles);
                $isContainerOpen = true;
                return $return;
            },
            $subject
        );

        if ($isContainerOpen) {
            $subject .= $this->closeContainer();
            $isContainerOpen = false;
        }

        // Convert end of lines
        $endOfLineConversion = $this->getConversionTag(self::END_OF_LINE_TAG);
        if ($endOfLineConversion !== PHP_EOL) {
            $subject = str_replace(PHP_EOL, $endOfLineConversion, $subject);
        }

        return $subject;
    }

    /**
     * @param array $styles
     * @return string
     */
    public function openContainerWithStyles(array $styles): string
    {
        $stylesContent = '';
        foreach ($styles as $style => $value) {
            $stylesContent .= $style . ':' . $value . ';';
        }
        return '<' . $this->getConversionTag(self::CONTAINER_TAG) . ' style="' . $stylesContent . '">';
    }

    /**
     * @return string
     */
    public function closeContainer(): string
    {
        return '</' . $this->getConversionTag(self::CONTAINER_TAG) . '>';
    }

    /**
     * @return array
     */
    public function getDefaultStyles()
    {
        $definedStyles = $this->getDefinedStyles();

        $defaultStyles = [];
        foreach (explode(';', $definedStyles[0]) as $style) {
            $styleParts = explode(':', $style);
            $defaultStyles[$styleParts[0]] = $styleParts[1];
        }
        return $defaultStyles;
    }

    /**
     * Define an array of css styles for a given code and default styles
     * @param string $code
     * @param array $styles
     * @return array
     */
    public function getCssStylesFromCode(string $code, array $styles): array
    {
        $code = trim($code);

        // 88/256 Colors
        $colorsMatches = null;
        if (preg_match('/(38|48);5;([0-9]+)/', $code, $colorsMatches)) {
            $colorNumber = (int) $colorsMatches[2];

            $isForegroundColor = (int) $colorsMatches[1] === 38;
            if ($isForegroundColor) {
                $styles['color'] = $this->getRgbColor($colorNumber);
            } else {
                // Background color
                $styles['background-color'] = $this->getRgbColor($colorNumber);
            }
            $code = trim(str_replace($colorsMatches[0], '', $code));
        }
        if ($code !== '') {
            // Defined styles codes
            $definedStyles = $this->getDefinedStyles();
            foreach (explode(';', $code) as $codePart) {
                $codePart = trim($codePart);
                if ($codePart === '') {
                    continue;
                }
                $codeNumber = (int) $codePart;
                if (isset($definedStyles[$codeNumber])) {
                    if ($definedStyles[$codeNumber]) {
                        foreach (explode(';', $definedStyles[$codeNumber]) as $style) {
                            $stylesPart = explode(':', $style);
                            $styles[$stylesPart[0]] = $stylesPart[1];
                        }
                    }
                }
            }
        }
        return $styles;
    }

    /**
     * @param string $tagName
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getConversionTag(string $tagName): string
    {
        if (!isset($this->conversionTags[$tagName])) {
            throw new \InvalidArgumentException('Argument "$tagName" "' . $tagName . '" is not an existing tag');
        }
        return $this->conversionTags[$tagName];
    }

    /**
     * @return array
     */
    public function getDefinedStyles(): array
    {
        return $this->definedStyles;
    }

    /**
     * @param array $definedStyles
     * @return \AnsiEscapesToHtml\Highlighter
     */
    public function setDefinedStyles(array $definedStyles)
    {
        $this->definedStyles = $definedStyles;
        return $this;
    }

    /**
     * @param integer $colorNumber
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getRgbColor(int $colorNumber): string
    {

        // Generate rgb colors array
        if (!$this->rgbColors) {
            for ($redIterator = 0; $redIterator < 6; $redIterator++) {
                for ($greenIterator = 0; $greenIterator < 6; $greenIterator++) {
                    for ($blueIterator = 0; $blueIterator < 6; $blueIterator++) {
                        $keyColorNumer = 16 + ($redIterator * 36) + ($greenIterator * 6) + $blueIterator;
                        $rgb = sprintf(
                            'rgb(%d,%d,%d)',
                            ($redIterator ? $redIterator * 40 + 55 : 0),
                            ($greenIterator ? $greenIterator * 40 + 55 : 0),
                            ($blueIterator ? $blueIterator * 40 + 55 : 0)
                        );
                        $this->rgbColors[$keyColorNumer] = $rgb;
                    }
                }
            }
            // Gray scale
            for ($grayIterator = 0; $grayIterator < 24; $grayIterator++) {
                $keyColorNumer = $grayIterator + 232;
                $gray = $grayIterator + 10 + 8;
                $this->rgbColors[$keyColorNumer] = 'rgb(' . $gray . $gray . $gray . ')';
            }
        }

        if (isset($this->rgbColors[$colorNumber])) {
            return $this->rgbColors[$colorNumber];
        }

        throw new \InvalidArgumentException(sprintf(
            'Argument "$colorNumber" "%d" expects to be in range 16,256',
            $colorNumber
        ));
    }
}
