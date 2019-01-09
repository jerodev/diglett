<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class NextFilter implements ICssFilter
{
    public function __construct(array $parameters)
    {
    }

    public static function getFunctionName(): string
    {
        return 'next';
    }

    public function filterNodes(Crawler $crawler): ?Crawler
    {
        if ($crawler->count() === 0) {
            return null;
        }

        $nextAll = $crawler->nextAll();
        if ($nextAll->count() === 0) {
            return null;
        }

        return $nextAll->first();
    }
}