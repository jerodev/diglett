<?php

use Jerodev\Diglett\Diglett;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class DiglettShouldNotBreakTest extends TestCase
{
    protected $diglett;

    protected function setUp()
    {
        $dom = trim('
            <div class="test">
                <ul>
                    <li>First</li>
                    <li>Second</li>
                </ul>
                <div class="content">
                    <p>First Paragraph</p>
                </div>
            </div>
        ');

        $this->diglett = new Diglett(new Crawler($dom));
    }

    public function testAttrShouldNotBreak()
    {
        $node = $this->diglett->filter('body')->children()->first();
        $this->assertEquals('test', $node->attr('class'));
        $this->assertNull($node->attr('data-none'));

        $node = $this->diglett->filter('ul.test li');
        $this->assertNull($node->attr('class'));
    }

    public function testFirstShouldNotBreak()
    {
        $node = $this->diglett->filter('ul li')->first();
        $this->assertNotNull($node->text());

        $node = $this->diglett->filter('ul.test li')->first();
        $this->assertNull($node->text());
    }

    public function testHtmlShouldNotBreak()
    {
        $node = $this->diglett->filter('.content')->first();
        $this->assertEquals('<p>First Paragraph</p>', $node->html());

        $node = $this->diglett->filter('ul.test');
        $this->assertNull($node->html());
    }

    public function testLastShouldNotBreak()
    {
        $node = $this->diglett->filter('ul li')->last();
        $this->assertNotNull($node->text());

        $node = $this->diglett->filter('ul.test li')->last();
        $this->assertNull($node->text());
    }

    public function testTextShouldNotBreak()
    {
        $node = $this->diglett->filter('ul li');
        $this->assertEquals('First', $node->text());

        $node = $this->diglett->filter('ul.test li');
        $this->assertNull($node->text());
    }
}
