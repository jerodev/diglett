<?php

use Jerodev\Diglett\Diglett;
use Jerodev\Diglett\WebClient;
use PHPUnit\Framework\TestCase;

class WebClientTest extends TestCase
{
    public function testBasicClient()
    {
        $diglett = WebClient::get('https://www.deviaene.eu/');

        $this->assertInstanceOf(Diglett::class, $diglett);
        $this->assertEquals('https://www.deviaene.eu/', $diglett->getCrawler()->getUri());
    }

    public function testSpecificClient()
    {
        $webClient = new WebClient([]);
        $diglett = $webClient->get('https://www.deviaene.eu/');

        $this->assertInstanceOf(Diglett::class, $diglett);
        $this->assertEquals('https://www.deviaene.eu/', $diglett->getCrawler()->getUri());
    }

    public function testConsecutiveRequests()
    {
        $webClient = new WebClient();
        $diglett = $webClient->get('https://www.deviaene.eu/');
        $this->assertInstanceOf(Diglett::class, $diglett);
        $this->assertEquals('https://www.deviaene.eu/', $diglett->getCrawler()->getUri());

        $diglett = $webClient->get('https://www.tabletopfinder.eu/en');
        $this->assertInstanceOf(Diglett::class, $diglett);
        $this->assertEquals('https://www.tabletopfinder.eu/en', $diglett->getCrawler()->getUri());
    }
}
