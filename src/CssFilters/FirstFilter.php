<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class FirstFilter implements ICssFilter
{
    public function __construct(array $parameters)
    {
    }

    public static function getFunctionName(): string
    {
        return 'first';
    }

    public function filterNodes(Crawler $crawler): ?Crawler
    {
        if ($crawler->count() === 0) {
            return null;
        } else {
            return $crawler->first();
        }
    }
}
