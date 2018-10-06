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

        switch ($name)
        {
            case 'getUri':
                return $this->crawler->{$name}(...$arguments);
                break;

            case 'attr':
            case 'evaluate':
            case 'nodeName':
            case 'text':
                return $this->hasNodeAvailable() ? $this->crawler->{$name}(...$arguments) : null;
                break;

            default:
                $this->crawler = $this->crawler->{$name}(...$arguments);
                break;
        }

        return $this;
    }

    /**
     *  Fetch the first element in a node list if available
     * 
     *  @return Diglett
     */
    public function first(): self {
        if ($this->hasNodeAvailable())
        {
            $this->crawler = $this->crawler->first();
        }
        else
        {
            $this->crawler = null;
        }

        return $this;
    }

    public function html(): ?string {
        return $this->hasNodeAvailable() ? trim($this->crawler->html()) : null;
    }

    /**
     *  Fetch the last element in a node list if available
     * 
     *  @return Diglett
     */
    public function last(): self {
        if ($this->hasNodeAvailable())
        {
            $this->crawler = $this->crawler->last();
        }
        else
        {
            $this->crawler = null;
        }

        return $this;
    }

    /**
     *  Check if the current crawler has any nodes
     * 
     *  @return bool
     */
    private function hasNodeAvailable(): bool {
        return $this->crawler !== null && $this->crawler->count() > 0;
    }

}