<?php

namespace Tests;

use ErrorException;
use Jerodev\Diglett\CssFilterParser;
use Jerodev\Diglett\CssFilters\FirstFilter;
use Jerodev\Diglett\CssFilters\NthFilter;
use Jerodev\Diglett\Models\ParsedSelector;
use PHPUnit\Framework\TestCase;

class CssFilterParserTest extends TestCase
{
    private $cssFilterParser;

    protected function setUp()
    {
        $this->cssFilterParser = new CssFilterParser([
            FirstFilter::class,
            NthFilter::class,
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
        new CssFilterParser([\Jerodev\Diglett\WebClient::class]);
    }

    public function testNoErrorOnCorrectlyImplementedFilters()
    {
        $cssFilterParser = new CssFilterParser([
            FirstFilter::class,
            NthFilter::class,
        ]);

        $this->assertInstanceOf(CssFilterParser::class, $cssFilterParser);
    }

    /**
     *  @dataProvider cssParserTestCaseProvider
     */
    public function testParsingCssSelectors($selector, $expectedResult)
    {
        $result = $this->cssFilterParser->parse($selector);

        for ($i = 0; $i < count($result); $i++) {
            $this->assertEquals($expectedResult[$i]->getSelector(), $result[$i]->getSelector());
            $this->assertEquals(count($expectedResult[$i]->getFunctions()), count($result[$i]->getFunctions()));

            // Test function types
            for ($j = 0; $j < $expectedResult[$i]->getFunctionCount(); $j++) {
                $this->assertInstanceOf(get_class($expectedResult[$i]->getFunction($j)), $result[$i]->getFunction($j));
            }
        }
    }

    public static function cssParserTestCaseProvider()
    {
        return [
            ['p', [new ParsedSelector('p')]],
            ['a[href]', [new ParsedSelector('a[href]')]],
            ['p.content a', [new ParsedSelector('p.content a')]],
            ['p.content a:nth(2)', [new ParsedSelector('p.content a', [new NthFilter([2])])]],
            ['p.content a:first() i', [new ParsedSelector('p.content a', [new FirstFilter([2])]), new ParsedSelector('i')]],
        ];
    }
}
