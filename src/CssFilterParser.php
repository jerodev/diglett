<?php

namespace Jerodev\Diglett;

use Jerodev\Diglett\CssFilters\ICssFilter;

class CssFilterParser
{
    /**
     *  The css filters to parse.
     *
     *  @var array
     */
    private $cssFilters;

    /**
     *  Create CssFilterParser and set the chosen css filters.
     *
     *  @param array $cssFilters An array of css filters to use
     */
    public function __construct(array $cssFilters = [])
    {
        $this->cssFilters = [];
        $this->addCssFilters([
            CssFilters\ContainsTextFilter::class,
            CssFilters\ExactTextFilter::class,
            CssFilters\FirstFilter::class,
            CssFilters\LastFilter::class,
            CssFilters\NextFilter::class,
            CssFilters\NthFilter::class,
            CssFilters\RegexTextFilter::class,
        ]);

        if (!empty($cssFilters)) {
            $this->addCssFilters($cssFilters);
        }
    }

    /**
     *  Add extra css filters.
     *
     *  @param array|string $cssFilter
     */
    public function addCssFilters($cssFilter): void
    {
        if (is_array($cssFilter)) {
            foreach ($cssFilter as $filter) {
                $this->addCssFilters($filter);
            }
        } else {
            if (!class_exists($cssFilter) || !in_array(ICssFilter::class, class_implements($cssFilter))) {
                throw new \ErrorException("`$cssFilter` does not implement ICssFilter.");
            }

            $this->cssFilters[$cssFilter::getFunctionName()] = $cssFilter;
        }
    }

    /**
     *  Parse a string to an array containing selectors and special functions.
     *
     *  @param string $line The filter to parser
     *
     *  @return array
     */
    public function parse(string $line): array
    {
        $line = trim($line);

        $parts = [];
        $selector = null;
        $functions = [];
        for ($i = 0; $i < strlen($line); $i++) {
            $char = $line[$i];
            if (empty(trim($char)) && empty(trim($selector))) {
                continue;
            }

            if ($char !== ':') {
                $selector .= $char;
            } else {
                do {
                    $brackets = 0;
                    $functionLine = '';
                    for (; ++$i < strlen($line);) {
                        $char = $line[$i];
                        $functionLine .= $char;
                        if ($char === '(') {
                            $brackets++;
                        } elseif ($char === ')' && --$brackets === 0) {
                            break;
                        }
                    }

                    $functions[] = $this->parseFunctionString($functionLine);
                } while (++$i < strlen($line) && $line[$i] === ':');

                $parts[] = ['selector' => $selector, 'functions' => $functions];
                $selector = null;
                $functions = [];
            }
        }

        if (!empty(trim($selector))) {
            $parts[] = ['selector' => $selector, 'functions' => $functions];
        }

        return $parts;
    }

    /**
     *  Parse a string to a CssFilter object.
     *
     *  @param string $line The part of the selector presenting the filter function
     */
    private function parseFunctionString(string $line): ICssFilter
    {
        if (!preg_match('/^([^\(]+)\((.*?)\)$/', $line, $matches)) {
            throw new \ErrorException("`$line` is not a valid function string.");
        }

        $functionName = $matches[1];
        if (!array_key_exists($functionName, $this->cssFilters)) {
            throw new \ErrorException("The ICssFilter `$functionName` does not exist.");
        }

        return new $this->cssFilters[$functionName](preg_split('/,/', $matches[2]));
    }
}
