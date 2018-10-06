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

    /**
     *  Functions we did not catch can be called directly on the crawler
     */
    public function __call($name, $arguments) {
        return $this->crawler->{$name}(...$arguments);
    }

    /**
     *  Fetch the first element in a node list if available
     * 
     *  @return Diglett
     */
    public function first(): self {
        if ($this->crawler !== null && $this->crawler->count() > 0)
        {
            $this->crawler = $this->crawler->first();
        }
        else
        {
            $this->crawler = null;
        }

        return $this;
    }

    /**
     *  Fetch the last element in a node list if available
     * 
     *  @return Diglett
     */
    public function last(): self {
        if ($this->crawler !== null && $this->crawler->count() > 0)
        {
            $this->crawler = $this->crawler->last();
        }
        else
        {
            $this->crawler = null;
        }

        return $this;
    }

}