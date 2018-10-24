> :hammer: This package is currently under development so function signatures can still change and the package might not even work

# Diglett Web Scraper
[![Build Status](https://travis-ci.org/jerodev/diglett.svg?branch=master)](https://travis-ci.org/jerodev/diglett) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jerodev/diglett/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jerodev/diglett/?branch=master)

Diglett is an extended web crawler based on the [Symfony DomCrawler Component](https://symfony.com/doc/current/components/dom_crawler.html). It allows to use extended and custom css selectors to easily get data from a web page.

## Requirements

- PHP 7.1.18 or higher

## How to use

    // TODO

## Build in selector functions

| Function  | Description | Example |
| --------- | ----------- | ------- |
| :first() | Get the first element in a collection | `ul li:first()` |
| :nth(x) | Get the nth element in a collection (starting at 1) | `ul li:nth(3)` |
