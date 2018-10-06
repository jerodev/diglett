<?php

namespace Jerodev\Diglett;

use Symfony\Component\DomCrawler\Crawler;

class Diglett {

    /**
     *  The Symfony DomCrawler to work with
     * 
     *  @var Crawler
     */
    private $crawler;

    /**
     *  A copy of the root crawler we started with
     * 
     *  @var Crawler
     */
    private $rootCrawler;

    /**
     *  Create a diglett instance from a Symfony Crawler
     * 
     *  @var Crawler $crawler
     */
    function __construct(Crawler $crawler) {

        $this->crawler = $crawler;
        $this->rootCrawler = $crawler;

    }

}