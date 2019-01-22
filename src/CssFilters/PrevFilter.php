<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class PrevFilter implements ICssFilter
{
    public function __construct(array $parameters)
    {
    }

    public static function getFunctionName(): string
    {
        return 'prev';
    }

    public function filterNodes(Crawler $crawler): ?Crawler
    {
        if ($crawler->count() === 0) {
            return null;
        }

        $prevAll = $crawler->previousAll();
        if ($prevAll->count() === 0) {
            return null;
        }

        return $prevAll->first();
    }
}
