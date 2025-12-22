<?php

namespace TestSuite;

use AnsiEscapesToHtml\Highlighter;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class HighlighterTest extends TestCase
{
    public const SIMPLE_TEXT = 'font-weight:normal;text-decoration:none;';
    public const UNDERLINE_TEXT = 'font-weight:normal;text-decoration:underline;';

    private readonly Highlighter $highlighter;

    protected function setUp(): void
    {
        $this->highlighter = new Highlighter();
    }

    public function testGetConversionTag()
    {
        $this->assertSame(
            'span',
            $this->highlighter->getConversionTag(Highlighter::CONTAINER_TAG)
        );
        $this->assertSame(
            '<br/>',
            $this->highlighter->getConversionTag(Highlighter::END_OF_LINE_TAG)
        );
    }

    public function testGetConversionTagUndefined()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "$tagName" "wrong" is not an existing tag');
        $this->highlighter->getConversionTag('wrong');
    }

    public function testSetDefinedStyles()
    {
        $this->assertSame($this->highlighter, $this->highlighter->setDefinedStyles(['test' => 'test']));
        $this->assertSame(['test' => 'test'], $this->highlighter->getDefinedStyles(['test' => 'test']));
    }

    public function testGetDefaultStyles()
    {
        $this->assertSame([
            'font-weight' => 'normal',
            'text-decoration' => 'none',
            'color' => 'White',
            'background-color' => 'Black',
        ], $this->highlighter->getDefaultStyles());
    }

    public function testGetCssStylesFromCode()
    {
        // Change color
        $this->assertSame([
            'font-weight' => 'normal',
            'text-decoration' => 'none',
            'color' => 'rgb(95,255,0)',
            'background-color' => 'Black',
        ], $this->highlighter->getCssStylesFromCode('38;5;82', $this->highlighter->getDefaultStyles()));

        // Reset
        $this->assertSame($this->highlighter->getDefaultStyles(), $this->highlighter->getCssStylesFromCode('0', [
            'font-weight' => 'normal',
            'text-decoration' => 'none',
            'color' => 'rgb(95,255,0)',
            'background-color' => 'Black',
        ]));
    }

    public function testGetRgbColor()
    {
        $this->assertSame('rgb(95,255,0)', $this->highlighter->getRgbColor(82));
        $this->assertSame('rgb(255,0,135)', $this->highlighter->getRgbColor(198));
    }

    public function testGetWrognRgbColorTrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "$colorNumber" "999" expects to be in range 16,256');
        $this->highlighter->getRgbColor(999);
    }

    public function testUnderlinedToHtml()
    {
        // echo -e "Normal \e[4mUnderlined \e[24mNormal"
        $expectedContent
            = '<span style="' . self::SIMPLE_TEXT . 'color:White;background-color:Black;">Normal </span>'
            . '<span style="' . self::UNDERLINE_TEXT . 'color:White;background-color:Black;">Underlined </span>'
            . '<span style="' . self::SIMPLE_TEXT . 'color:White;background-color:Black;">Normal</span>';

        $this->assertSame(
            $expectedContent,
            $this->highlighter->toHtml('Normal [4mUnderlined [24mNormal')
        );
    }

    public function testForegroundBlueToHtml()
    {
        // echo -e "Default \e[34mBlue"
        $expectedContent
            = '<span style="' . self::SIMPLE_TEXT . 'color:White;background-color:Black;">Default </span>'
            . '<span style="' . self::SIMPLE_TEXT . 'color:Blue;background-color:Black;">Blue</span>';

        $this->assertSame(
            $expectedContent,
            $this->highlighter->toHtml('Default [34mBlue')
        );
    }

    public function test256ForegroundColorToHtml()
    {
        // echo -e "\e[38;5;82mHello \e[38;5;198mWorld"
        $expectedContent
            = '<span style="' . self::SIMPLE_TEXT . 'color:rgb(95,255,0);background-color:Black;">Hello </span>'
            . '<span style="' . self::SIMPLE_TEXT . 'color:rgb(255,0,135);background-color:Black;">World</span>';

        $this->assertSame(
            $expectedContent,
            $this->highlighter->toHtml('[38;5;82mHello [38;5;198mWorld')
        );
    }

    public function test256BackgroundColorToHtml()
    {
        // echo -e "\e[40;38;5;82m Hello \e[30;48;5;82m World \e[0m"
        $expectedContent
            = '<span style="' . self::SIMPLE_TEXT . 'color:rgb(95,255,0);background-color:Black;"> Hello </span>'
            . '<span style="' . self::SIMPLE_TEXT . 'color:Black;background-color:rgb(95,255,0);"> World </span>'
            . '<span style="' . self::SIMPLE_TEXT . 'color:White;background-color:Black;"></span>';

        $this->assertSame(
            $expectedContent,
            $this->highlighter->toHtml('[40;38;5;82m Hello [30;48;5;82m World [0m')
        );
    }
}
