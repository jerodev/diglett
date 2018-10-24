<?php

namespace Jerodev\Diglett\CssFilters;

use Symfony\Component\DomCrawler\Crawler;

class ContainsTextFilter implements ICssFilter
{
    private $text;

    public function __construct(array $parameters) 
    {
        $this->text = null;
        if (count($parameters) > 0)
        {
            $this->text = $parameters[0];
        }
    }

    public static function getFunctionName(): string
    {
        return 'containstext';
    }

    public function filterNodes(Crawler $crawler): ?Crawler {

        if ($crawler->count() === 0)
        {
            return null;
        }
        elseif (empty($this->text))
        {
            return $crawler;
        }
        else
        {
            $text = $this->text;
            return $crawler->reduce(function ($node) use ($text) {
                return strpos($node->text(), $text) !== false;
            });
        }

    }
}