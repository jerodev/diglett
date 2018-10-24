<?php

use Jerodev\Diglett\CssFilterParser;
use Jerodev\Diglett\CssFilters\FirstFilter;
use Jerodev\Diglett\CssFilters\NthFilter;
use PHPUnit\Framework\TestCase;

class CssFilterParserTest extends TestCase
{
    private $cssFilterParser;

    protected function setUp()
    {
        $this->cssFilterParser = new CssFilterParser([
            FirstFilter::class,
            NthFilter::class
        ]);
    }

    public function testThrowErrorOnUknownCssFilterClass()
    {
        $this->expectException(ErrorException::class);

        $this->cssFilterParser->parse('a[href]:qsdf()');
    }

    public function testThrowErrorOnFilterThatDoesNotImplementInterface() 
    {
        $this->expectException(ErrorException::class);
        new CssFilterParser([ \Jerodev\Diglett\WebClient::class ]);
    }

    public function testNoErrorOnCorrectlyImplementedFilters()
    {
        $cssFilterParser = new CssFilterParser([
            FirstFilter::class, 
            NthFilter::class 
        ]);

        $this->assertInstanceOf(CssFilterParser::class, $cssFilterParser);
    }

    /**
     *  @dataProvider cssParserTestCaseProvider
     */
    public function testParsingCssSelectors($selector, $expectedResult)
    {
        $result = $this->cssFilterParser->parse($selector);
        
        for ($i = 0; $i < count($result); $i++)
        {
            $this->assertEquals($expectedResult[$i]['selector'], $result[$i]['selector']);
            $this->assertEquals(count($expectedResult[$i]['functions']), count($result[$i]['functions']));

            // Test function types
            for ($j = 0; $j < count($expectedResult[$i]['functions']); $j++)
            {
                $this->assertInstanceOf(get_class($expectedResult[$i]['functions'][$j]), $result[$i]['functions'][$j]);
            }
        }
    }

    public static function cssParserTestCaseProvider()
    {
        return [
            ['p', [['selector' => 'p', 'functions' => []]]],
            ['a[href]', [['selector' => 'a[href]', 'functions' => []]]],
            ['p.content a', [['selector' => 'p.content a', 'functions' => []]]],
            ['p.content a:nth(2)', [['selector' => 'p.content a', 'functions' => [new NthFilter([2])]]]],
            ['p.content a:first() i', [['selector' => 'p.content a', 'functions' => [new FirstFilter([2])]], ['selector' => 'i', 'functions' => []]]]
        ];
    }
}