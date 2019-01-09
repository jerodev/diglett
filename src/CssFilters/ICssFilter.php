<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

interface ICssFilter
{
    public function __construct(array $parameters);

    public function filterNodes(Crawler $crawler): ?Crawler;

    public static function getFunctionName(): string;
}
