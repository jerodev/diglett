<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class RegexTextFilter implements ICssFilter
{
    private $regex;

    public function __construct(array $parameters)
    {
        $this->regex = null;
        if (count($parameters) > 0) {
            $this->regex = $parameters[0];
        }
    }

    public static function getFunctionName(): string
    {
        return 'regextext';
    }

    public function filterNodes(Crawler $crawler): ?Crawler
    {
        if ($crawler->count() === 0) {
            return null;
        } elseif (empty($this->regex)) {
            return $crawler;
        } else {
            $regex = $this->regex;

            return $crawler->reduce(function ($node) use ($regex) {
                return preg_match("/$regex/", $node->text()) === 1;
            });
        }
    }
}
