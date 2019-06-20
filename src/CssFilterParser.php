<?php

namespace Jerodev\Diglett;

use ErrorException;
use Jerodev\Diglett\CssFilters\ICssFilter;
use Jerodev\Diglett\Models\ParsedSelector;

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
     * @param array $cssFilters An array of css filters to use
     *
     * @throws ErrorException
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
            CssFilters\PrevFilter::class,
            CssFilters\RegexTextFilter::class,
        ]);

        if (!empty($cssFilters)) {
            $this->addCssFilters($cssFilters);
        }
    }

    /**
     *  Add extra css filters.
     *
     * @param array|string $cssFilter
     *
     * @throws ErrorException
     */
    public function addCssFilters($cssFilter): void
    {
        if (is_array($cssFilter)) {
            foreach ($cssFilter as $filter) {
                $this->addCssFilters($filter);
            }
        } else {
            if (!class_exists($cssFilter) || !in_array(ICssFilter::class, class_implements($cssFilter))) {
                throw new ErrorException("`$cssFilter` does not implement ICssFilter.");
            }

            $this->cssFilters[$cssFilter::getFunctionName()] = $cssFilter;
        }
    }

    /**
     * Parse a string to an array containing selectors and special functions.
     *
     * @param string $line The filter to parser.
     *
     * @throws ErrorException
     *
     * @return array
     */
    public function parse(string $line): array
    {
        $line = trim($line);

        $parts = [];
        $selector = null;
        $functions = [];
        $quoted = false;
        for ($i = 0; $i < strlen($line); $i++) {
            $char = $line[$i];
            if (empty(trim($char)) && empty(trim($selector))) {
                continue;
            }
            if ($char === '"') {
                $quoted = !$quoted;
            }

            if ($char !== ':' || $quoted) {
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

                $parts[] = new ParsedSelector($selector, $functions);
                $selector = null;
                $functions = [];
            }
        }

        if (!empty(trim($selector)) || !empty($functions)) {
            $parts[] = new ParsedSelector($selector, $functions);
        }

        return $parts;
    }

    /**
     * Parse a string to a CssFilter object.
     *
     * @param string $line The part of the selector presenting the filter function.
     *
     * @throws ErrorException
     *
     * @return ICssFilter
     */
    private function parseFunctionString(string $line): ICssFilter
    {
        $functionName = strstr($line, '(', true);
        $arguments = explode(',', substr(strstr($line, '('), 1, -1));

        if (!array_key_exists($functionName, $this->cssFilters)) {
            throw new ErrorException("The ICssFilter `$functionName` does not exist.");
        }

        return new $this->cssFilters[$functionName]($arguments);
    }
}
