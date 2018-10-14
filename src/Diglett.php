<?php

namespace Jerodev\Diglett;

use Symfony\Component\DomCrawler\Crawler;

class Diglett
{
    /**
     *  The Symfony DomCrawler to work with.
     *
     *  @var Crawler
     */
    private $crawler;

    /**
     *  An array of special ICssFilter's
     * 
     *  @var array
     */
    private $cssFilters;

    /**
     *  Create a diglett instance from a Symfony Crawler.
     *
     *  @param Crawler
     *  @param array|null $cssFilters
     */
    public function __construct(Crawler $crawler, ?array $cssFilters = null)
    {
        $this->crawler = $crawler;

        // If no css filters are set, add the default list
        if ($cssFilters === null)
        {
            $cssFilters = [
                CssFilters\NthFilter::class
            ];
        }
        $this->cssFilters = [];
        foreach ($cssFilters as $filter)
        {
            $this->cssFilters[$filter::getFunctionName()] = $filter;
        }
    }

    /**
     *  Get the underlying crawler object
     * 
     *  @return Crawler
     */
    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }


    /**
     *  Use special css selectors to fetch several values
     * 
     *  @param array $selectors
     *  @return array
     */
    public function getTexts(array $selectors): array
    {
        $results = [];
        foreach ($selectors as $key => $value)
        {
            $results[$key] = $this->getText($value);
        }

        return $results;
    }

    /**
     *  Get the value for a single special css selector
     * 
     *  @param string $selector
     *  @return string
     */
    public function getText(string $selector): string 
    {
        $parsedSelector = CssFilterParser::parse($selector);
        die('TODO: use the parsed selector');
    }
}
