<?php

use Jerodev\Diglett\{Diglett, WebClient};
use PHPUnit\Framework\TestCase;

class WebClientTest extends TestCase {
    
    public function testBasicClient() {
        
        $diglett = WebClient::get('https://www.deviaene.eu/');

        $this->assertInstanceOf(Diglett::class, $diglett);

    }

    public function testSpecificClient() {
        
        $webClient = new WebClient([]);
        $diglett = $webClient->get('https://www.deviaene.eu/');

        $this->assertInstanceOf(Diglett::class, $diglett);

    }

}