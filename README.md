# Diglett Web Scraper
[![Build Status](https://travis-ci.org/jerodev/diglett.svg?branch=master)](https://travis-ci.org/jerodev/diglett) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jerodev/diglett/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jerodev/diglett/?branch=master) [![StyleCI](https://github.styleci.io/repos/151305583/shield?branch=master)](https://github.styleci.io/repos/151305583)

Diglett is an extended web crawler based on the [Symfony DomCrawler Component](https://symfony.com/doc/current/components/dom_crawler.html). It allows to use extended and custom css selectors to easily get data from a web page.

## Requirements
- PHP 7.1.18 or higher

## How to use
Diglett includes a webclient that returns a Diglett instance, but you can also inject your own Symfony Crawler object into the Diglett class. From your Diglett object, you can start using the different functions that implement the specialized css filter functions.

```php
$diglett = \Jerodev\Diglett\WebClient::get('https://www.tabletopfinder.eu/');
$firstParagraph = $diglett->getText("p:first()");
```

## Built-in selector functions
| Function  | Description | Example |
| --------- | ----------- | ------- |
| **:containsregex(str)** | Get the elements where the text content matches a regular expression | `div p:containsregex([Hh]el+o)` |
| **:containstext(str)** | Get the elements where the text content contain this substring | `div p:containstext(Hello World)` |
| **:first()** | Get the first element in a collection | `ul li:first()` |
| **:last()** | Get the last element in a collection | `ul li:last()` |
| **:next()** | Get the first sibling to the current element if available | `ul.test:next() li` |
| **:nth(x)** | Get the nth element in a collection (starting at 1) | `ul li:nth(3)` |
| **:prev()** | Get previous sibling to the current element if available | `ul li:last():prev()` |
| **:text(str)** | Get elements that exactly have this innerText | `ul li:text(Hello World)` |
