<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class FirstFilter implements ICssFilter 
{
    function __construct(array $parameters) { }

    static function getFunctionName(): string 
    {
        return 'first';
    }

    function filterNodes(Crawler $crawler): ?Crawler {

        if ($crawler->count() === 0)
        {
            return null;
        }
        else
        {
            return $crawler->first();
        }

    }
}