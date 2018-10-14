<?php

use Jerodev\Diglett\CssFilterParser;
use Jerodev\Diglett\CssFilters\NthFilter;
use PHPUnit\Framework\TestCase;

class CssFilterParserTest extends TestCase
{
    /**
     *  Having an unknown css filter function should throw an ErrorException
     */
    public function testThrowErrorOnUknownCssFilterClass()
    {
        $this->expectException(ErrorException::class);

        CssFilterParser::parse('a[href]:first()');
    }

    /**
     *  @dataProvider cssParserTestCaseProvider
     */
    public function testParsingCssSelectors($selector, $expectedResult)
    {
        $result = CssFilterParser::parse($selector, [
            'nth' => NthFilter::class
        ]);
        
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
            ['p.content a:nth(2)', [['selector' => 'p.content a', 'functions' => [new NthFilter([2])]]]]
        ];
    }
}