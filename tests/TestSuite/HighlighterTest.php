<?php

namespace TestSuite;

class HighlighterTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        $this->highlighter = new \AnsiEscapesToHtml\Highlighter();
    }

    public function testGetConversionTag()
    {
        $this->assertSame('span', $this->highlighter->getConversionTag(\AnsiEscapesToHtml\Highlighter::CONTAINER_TAG));
        $this->assertSame('<br/>', $this->highlighter->getConversionTag(\AnsiEscapesToHtml\Highlighter::END_OF_LINE_TAG));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "$sTagName" expects a string, "NULL" given
     */
    public function testGetConversionTagWithWrongArgument()
    {
        $this->highlighter->getConversionTag(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "$sTagName" "wrong" is not an existing tag
     */
    public function testGetConversionTagUndefined()
    {
        $this->highlighter->getConversionTag('wrong');
    }

    public function testSetDefinedStyles()
    {
        $this->assertSame($this->highlighter, $this->highlighter->setDefinedStyles(array('test' => 'test')));
        $this->assertSame(array('test' => 'test'), $this->highlighter->getDefinedStyles(array('test' => 'test')));
    }

    public function testGetDefaultStyles()
    {
        $this->assertSame(array(
            'font-weight' => 'normal',
            'text-decoration' => 'none',
            'color' => 'White',
            'background-color' => 'Black',
                ), $this->highlighter->getDefaultStyles());
    }

    public function testGetCssStylesFromCode()
    {
        // Change color
        $this->assertSame(array(
            'font-weight' => 'normal',
            'text-decoration' => 'none',
            'color' => 'rgb(95,255,0)',
            'background-color' => 'Black',
                ), $this->highlighter->getCssStylesFromCode('38;5;82', $this->highlighter->getDefaultStyles()));

        // Reset
        $this->assertSame($this->highlighter->getDefaultStyles(), $this->highlighter->getCssStylesFromCode('0', array(
                    'font-weight' => 'normal',
                    'text-decoration' => 'none',
                    'color' => 'rgb(95,255,0)',
                    'background-color' => 'Black',
        )));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "$sCode" expects a string, "NULL" given
     */
    public function testGetCssStylesFromCodeWithWrongArgument()
    {
        $this->highlighter->getCssStylesFromCode(null, array());
    }

    public function testGetRgbColor()
    {
        $this->assertSame('rgb(95,255,0)', $this->highlighter->getRgbColor(82));
        $this->assertSame('rgb(255,0,135)', $this->highlighter->getRgbColor(198));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "$iColorNumber" expects an integer, "NULL" given
     */
    public function testGetRgbColorWithWrongArgument()
    {
        $this->highlighter->getRgbColor(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "$sSubject" expects a string, "NULL" given
     */
    public function testToHtmlWithWrongArgument()
    {
        $this->highlighter->toHtml(null);
    }

    public function testUnderlinedToHtml()
    {
        // echo -e "Normal \e[4mUnderlined \e[24mNormal"
        $sExpectedNormal1 = '<span style="font-weight:normal;text-decoration:none;color:White;background-color:Black;">Normal </span>';
        $sExpectedUnderlined = '<span style="font-weight:normal;text-decoration:underline;color:White;background-color:Black;">Underlined </span>';
        $sExpectedNormal2 = '<span style="font-weight:normal;text-decoration:none;color:White;background-color:Black;">Normal</span>';
        $this->assertSame($sExpectedNormal1 . $sExpectedUnderlined . $sExpectedNormal2, $this->highlighter->toHtml('Normal [4mUnderlined [24mNormal'));
    }

    public function testForegroundBlueToHtml()
    {
        // echo -e "Default \e[34mBlue"
        $sExpectedDefault = '<span style="font-weight:normal;text-decoration:none;color:White;background-color:Black;">Default </span>';
        $sExpectedBlue = '<span style="font-weight:normal;text-decoration:none;color:Blue;background-color:Black;">Blue</span>';
        $this->assertSame($sExpectedDefault . $sExpectedBlue, $this->highlighter->toHtml('Default [34mBlue'));
    }

    public function test256ForegroundColorToHtml()
    {
        // echo -e "\e[38;5;82mHello \e[38;5;198mWorld"
        $sExpectedHello = '<span style="font-weight:normal;text-decoration:none;color:rgb(95,255,0);background-color:Black;">Hello </span>';
        $sExpectedWorld = '<span style="font-weight:normal;text-decoration:none;color:rgb(255,0,135);background-color:Black;">World</span>';
        $this->assertSame($sExpectedHello . $sExpectedWorld, $this->highlighter->toHtml('[38;5;82mHello [38;5;198mWorld'));
    }

    public function test256BackgroundColorToHtml()
    {
        // echo -e "\e[40;38;5;82m Hello \e[30;48;5;82m World \e[0m"
        $sExpectedHello = '<span style="font-weight:normal;text-decoration:none;color:rgb(95,255,0);background-color:Black;"> Hello </span>';
        $sExpectedWorld = '<span style="font-weight:normal;text-decoration:none;color:Black;background-color:rgb(95,255,0);"> World </span>';
        $sExpectedReset = '<span style="font-weight:normal;text-decoration:none;color:White;background-color:Black;"></span>';
        $this->assertSame($sExpectedHello . $sExpectedWorld . $sExpectedReset, $this->highlighter->toHtml('[40;38;5;82m Hello [30;48;5;82m World [0m'));
    }
}
