<?php

use Jerodev\Diglett\Diglett;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 *  Test text fetching functions on Diglett
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
            </div>
        ');
        $this->diglett = new Diglett(new Crawler($dom));
    }

    /**
     *  @dataProvider diglettTestCaseProvider
     */
    public function testGetText(string $selector, string $expectedResult) {

        $this->assertEquals($expectedResult, $this->diglett->getText($selector));

    }

    public function testGetAllTexts() {
        $cases = [];
        $results = [];
        array_map(function ($value) use (&$cases, &$results) { $cases[] = $value[0]; $results[] = $value[1]; }, $this->diglettTestCaseProvider());

        $this->assertEquals($results, $this->diglett->getTexts($cases));
    }

    public static function diglettTestCaseProvider(): array
    {
        return [
            ['p', 'This is the intro'],
            ['.content li:nth(4)', 'Four'],
            ['ul:first(){data-nth}', '1']
        ];
    }

}
