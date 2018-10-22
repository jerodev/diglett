<?php

use Jerodev\Diglett\CssFilters\FirstFilter;
use Jerodev\Diglett\CssFilters\NthFilter;
use Jerodev\Diglett\Diglett;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 *  Test basic Diglett implementation
 */
class DiglettTest extends TestCase
{
    
    public function testCssFilterDoesNotImplementInterface() 
    {
        $this->expectException(ErrorException::class);
        new Diglett(new Crawler, [ \Jerodev\Diglett\WebClient::class ]);
    }

    public function testCssFilterDoesImplementInterface()
    {
        $diglett = new Diglett(new Crawler, [
            FirstFilter::class, 
            NthFilter::class 
        ]);

        $this->assertInstanceOf(Diglett::class, $diglett);
    }

}
