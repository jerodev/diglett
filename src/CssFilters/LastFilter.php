<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class LastFilter implements ICssFilter
{
    public function __construct(array $parameters)
    {
    }

    public static function getFunctionName(): string
    {
        return 'last';
    }

    public function filterNodes(Crawler $crawler): ?Crawler
    {
        if ($crawler->count() === 0) {
            return null;
        } else {
            return $crawler->last();
        }
    }
}
