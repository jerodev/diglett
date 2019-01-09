<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class ExactTextFilter implements ICssFilter
{
    private $text;

    public function __construct(array $parameters)
    {
        $this->text = null;
        if (count($parameters) > 0) {
            $this->text = $parameters[0];
        }
    }

    public static function getFunctionName(): string
    {
        return 'text';
    }

    public function filterNodes(Crawler $crawler): ?Crawler
    {
        if ($crawler->count() === 0) {
            return null;
        }

        $text = $this->text;
        return $crawler->reduce(function ($node) use ($text) {
            return $node->text() === $text;
        });

    }
}