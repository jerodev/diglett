<?php

namespace Tests;

use Jerodev\Diglett\Diglett;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 *  Test text fetching functions on Diglett.
 */
class DiglettTextTest extends TestCase
{
    private $diglett;

    protected function setUp()
    {
        $dom = trim('
            <div class="content">
                <p>This is the intro</p>
                <ul data-nth="1">
                    <li>One</li>
                    <li>Two</li>
                    <li>Three</li>
                </ul>
                <ul class="second-ul" data-nth="2">
                    <li>Four</li>
                    <li>Five</li>
                </ul>
                <div class="urls">
                    <a href="/test.html">Local test</a>
                    <a href="relative.html">Relative link</a>
                    <a href="https://www.tabletopfinder.eu/">TableTopFinder</a>
                </div>
            </div>
        ');
        $this->diglett = new Diglett(new Crawler($dom, 'https://www.google.com/q/a?test=1'));
    }

    public function testEach()
    {
        $array = $this->diglett->each('div.content ul:first() li', function ($diglett, $i) {
            return $i.$diglett->getText();
        });

        $this->assertEquals(['0One', '1Two', '2Three'], $array);
    }

    /**
     *  @dataProvider diglettTestCaseProvider
     */
    public function testGetText(string $selector, ?string $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->diglett->getText($selector));
    }

    public function testGetAllTexts()
    {
        $cases = [];
        $results = [];
        array_map(function ($value) use (&$cases, &$results) {
            $cases[] = $value[0];
            $results[] = $value[1];
        }, $this->diglettTestCaseProvider());

        $this->assertEquals($results, $this->diglett->getTexts($cases));
    }

    public function testGetUrls()
    {
        $this->assertEquals(
            ['https://www.google.com/test.html', 'https://www.google.com/q/relative.html', 'https://www.tabletopfinder.eu/'],
            $this->diglett->getUrls('.urls a')
        );
    }

    public function testNestedFilter()
    {
        $result = $this->diglett->each('ul', function ($diglett, $i) {
            return $diglett->getText(str_repeat(':prev()', $i + 1));
        });

        $this->assertEquals(['This is the intro', 'This is the intro'], $result);
    }

    public static function diglettTestCaseProvider(): array
    {
        return [
            ['.content li:nth(4)', 'Four'],
            ['.content li:nth(7)', null],
            ['p', 'This is the intro'],
            ['ul[data-nth=1]:first() li:nth(2)', 'Two'],
            ['ul:first(){data-nth}', '1'],
            ['ul li:containstext(wo)', 'Two'],
            ['ul li:containstext(two)', null],
            ['ul li:last()', 'Five'],
            ['ul li:last():next()', null],
            ['ul li:last():next():next()', null],
            ['ul li:regextext([Ff][uo]+r)', 'Four'],
            ['ul li:regextext(f[ou]+r)', null],
            ['ul li:regextext(T(wo|hree)):first()', 'Two'],
            ['ul li:text(Two)', 'Two'],
            ['ul li:text(Two):next()', 'Three'],
            ['ul li:text(Tw)', null],
            ['ul li:last():prev()', 'Four'],
            ['ul li:first():prev()', null],
        ];
    }
}
