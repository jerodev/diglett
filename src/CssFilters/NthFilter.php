<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class NthFilter implements ICssFilter
{
    private $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public static function getFunctionName(): string
    {
        return 'nth';
    }

    public function filterNodes(Crawler $crawler): ?Crawler
    {
        // Nth needs one parameter
        if (count($this->parameters) === 0) {
            throw new \ErrorException(':nth(x) css selector should have at least one parameter');
        }

        // If not enough nodes in the list, return null
        $count = intval($this->parameters[0]);
        if ($crawler->count() < $count) {
            return null;
        }

        // Get the nth element
        return $crawler->eq($count - 1);
    }
}