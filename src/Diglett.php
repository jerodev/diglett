<?php

namespace Jerodev\Diglett;

use Symfony\Component\DomCrawler\Crawler;

class Diglett
{
    /**
     *  The Symfony DomCrawler to work with.
     *
     *  @var Crawler
     */
    private $crawler;

    /**
     *  The css selector parser
     *
     *  @var CssFilterParser
     */
    private $cssFilterParser;

    /**
     *  Create a diglett instance from a Symfony Crawler.
     *
     *  @param Crawler
     *  @param array $cssFilter An array of extra ICssFilterl classes to filter on
     */
    public function __construct(Crawler $crawler, array $cssFilters = [])
    {
        $this->crawler = $crawler;
        $this->cssFilterParser = new CssFilterParser($cssFilters);
    }

    /**
     *  Get the underlying crawler object
     *
     *  @return Crawler
     */
    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    /**
     *  Use special css selectors to filter on the current node collection
     *
     *  @param string $selector
     *  @return Crawler|null
     */
    public function filter(string $selector): ?Crawler
    {
        $parsedSelector = $this->cssFilterParser->parse($selector);

        $crawler = $this->getCrawler();
        foreach ($parsedSelector as $part)
        {
            $crawler = $crawler->filter($part['selector']);

            foreach ($part['functions'] as $function)
            {
                $crawler = $function->filterNodes($crawler);
                if ($crawler === null)
                {
                    return null;
                }
            }

            if (empty($crawler) || $crawler->count() === 0)
            {
                break;
            }
        }

        return $crawler;
    }


    /**
     *  Use special css selectors to fetch several values
     *
     *  @param array $selectors
     *  @return array
     */
    public function getTexts(array $selectors): array
    {
        $results = [];
        foreach ($selectors as $key => $value)
        {
            $results[$key] = $this->getText($value);
        }

        return $results;
    }

    /**
     *  Get the value for a single special css selector
     *
     *  @param string $selector
     *  @return string|null
     */
    public function getText(string $selector): ?string
    {
        $attribute = null;
        $selector = preg_replace_callback(
            '/\{(.*?)\}$/',
            function ($matches) use (&$attribute) {
                $attribute = $matches[1] ?? null;
            },
            $selector
        );

        $crawler = $this->filter($selector);
        if ($crawler === null || $crawler->count() === 0)
        {
            return null;
        }

        return $attribute === null ? $crawler->text() : $crawler->attr($attribute);
    }
}
