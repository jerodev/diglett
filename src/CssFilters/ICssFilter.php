<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

interface ICssFilter
{
    function __construct(array $parameters);
    function filterNodes(Crawler $crawler): ?Crawler;

    static function getFunctionName(): string;
}