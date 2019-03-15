<?php

namespace Jerodev\Diglett;

use Closure;
use Jerodev\Diglett\CssFilters\ICssFilter;
use Symfony\Component\DomCrawler\Crawler;

class Diglett
{
    /**
     *  The Symfony DomCrawler to work with.
     *
     *  @var Crawler|null
     */
    private $crawler;

    /**
     *  The css selector parser.
     *
     *  @var CssFilterParser
     */
    private $cssFilterParser;

    /**
     *  Create a diglett instance from a Symfony Crawler.
     *
     *  @param Crawler|null $crawler
     *  @param ICssFilter[] $cssFilters An array of extra ICssFilterl classes to filter on
     */
    public function __construct(?Crawler $crawler = null, array $cssFilters = [])
    {
        $this->crawler = $crawler;
        $this->cssFilterParser = new CssFilterParser($cssFilters);
    }

    /**
     *  Get the underlying crawler object.
     *
     *  @return Crawler|null
     */
    public function getCrawler(): ?Crawler
    {
        return $this->crawler;
    }

    /**
     *  Perform a closure function on matched nodes for a selector and return as array.
     *
     *  @param string $selector
     *  @param Closure $closure A function to perform on the list of nodes
     *
     *  @return array An array of results returned by the closure
     */
    public function each(string $selector, Closure $closure): array
    {
        return $this->filter($selector)->getCrawler()->each(function ($crawler, $i) use ($closure) {
            return $closure(new self($crawler), $i);
        });
    }

    /**
     *  Use special css selectors to filter on the current node collection.
     *
     *  @param string $selector
     *
     *  @return Diglett
     */
    public function filter(string $selector): self
    {
        $parsedSelector = $this->cssFilterParser->parse($selector);

        $crawler = $this->getCrawler();
        foreach ($parsedSelector as $part) {
            if (empty($crawler) || $crawler->count() === 0) {
                break;
            }

            if (!empty($part->getSelector())) {
                $crawler = $crawler->filter($part->getSelector());
            }

            foreach ($part->getFunctions() as $function) {
                $crawler = $function->filterNodes($crawler);
                if ($crawler === null) {
                    break;
                }
            }
        }

        return new self($crawler);
    }

    /**
     *  Use special css selectors to fetch several values.
     *
     *  @param string[] $selectors
     *
     *  @return array
     */
    public function getTexts(array $selectors): array
    {
        $results = [];
        foreach ($selectors as $key => $value) {
            $results[$key] = $this->getText($value);
        }

        return $results;
    }

    /**
     *  Get the value for a single special css selector.
     *
     *  @param string $selector
     *
     *  @return string|null
     */
    public function getText(?string $selector = null): ?string
    {
        $attribute = null;
        $diglett = $this;

        if ($selector !== null) {
            if (($attr = strstr($selector, '{')) && $attr[-1] === '}') {
                $selector = substr($selector, 0, strlen($attr) * -1);
                $attribute = substr($attr, 1, -1);
            }

            $diglett = $this->filter($selector);
        }

        if ($diglett->nodeCount() === 0) {
            return null;
        }

        $crawler = $diglett->getCrawler();

        return $attribute === null ? $crawler->text() : $crawler->attr($attribute);
    }

    /**
     *  Fetch urls from the selected nodes (a[href], img[src]).
     */
    public function getUrls(string $selector): array
    {
        $diglett = $this->filter($selector);
        if ($diglett->nodeCount() === 0) {
            return [];
        }

        $crawler = $diglett->getCrawler();
        $absolute = implode('/', array_slice(explode('/', $crawler->getUri()), 0, 3)).'/';
        $relative = substr(strstr($crawler->getUri(), '?', true) ?: $crawler->getUri(), 0, strrpos($crawler->getUri(), '/') + 1);

        return $crawler
            ->reduce(function ($node) {
                return in_array(strtolower($node->nodeName()), ['a', 'img']);
            })
            ->each(function ($node) use ($absolute, $relative) {
                $url = null;
                switch (strtolower($node->nodeName())) {
                    case 'a':
                        $url = $node->attr('href');
                        break;

                    case 'img':
                        $url = $node->attr('src');
                        break;
                }

                if (!in_array(substr($url, 0, 7), ['http://', 'https:/'])) {
                    if ($url[0] === '/') {
                        $url = $absolute.ltrim($url, '/');
                    } else {
                        $url = $relative.ltrim($url, '/');
                    }
                }

                return $url;
            });
    }

    /**
     *  Find the node count on the current crawler instance.
     */
    public function nodeCount(): int
    {
        if ($this->crawler === null) {
            return 0;
        }

        return $this->crawler->count();
    }
}
