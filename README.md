# Diglett web scraper [![Build Status](https://travis-ci.org/jerodev/diglett.svg?branch=master)](https://travis-ci.org/jerodev/diglett)
Diglett is an unbreakable webcrawler written in php using the [Symfony DomCrawler Component](https://symfony.com/doc/current/components/dom_crawler.html). This library makes it so that the available functions on the crawler can never throw an error and adds some extra functionalities.

> :exclamation: This package is under development and does currently not work

## But why?
I have several websites that need to constantly crawl different websites and needed a crawler that easily fetches data without abruptly stopping. Diglett is made so that I can throw a list of special css selectors at it and it returns me the data if found.