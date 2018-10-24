<?php

namespace Jerodev\Diglett;

class CssFilterParser {

    /**
     *  Parse a string to an array containing selectors and special functions
     *
     *  @param string $line The filter to parser
     *  @param array $cssFilters A list of special css filters
     *  @return array
     */
    public static function parse(string $line, array $cssFilters = []): array {

        // Get basic parsed string
        $parsed = self::parseString($line);

        // Match css filter classes
        $result = [];
        foreach ($parsed as $selector)
        {
            if (!empty($selector['functions']))
            {
                $functions = [];
                foreach ($selector['functions'] as $func)
                {
                    if (!array_key_exists($func['function'], $cssFilters))
                        throw new \ErrorException('No CssFilter class found for \':' . $func['function'] . '\'.');

                    $functions[] = new $cssFilters[$func['function']](preg_split('/\s*,\s*/', $func['data']));
                }

                $selector['functions']  = $functions;
            }

            $result[] = $selector;
        }

        return $result;

    }

    /**
     *  Parse a css string to an array of selectors and special functions
     *
     *  @param string $line
     *  @return array
     */
    private static function parseString(string $line): array {

        $line = trim($line);

        $parts = [];
        $selec = null; $funcs = [];
        for ($i = 0; $i < strlen($line); $i++)
        {
            $char = $line[$i];
            if (empty(trim($char)) && empty(trim($selec)))
                continue;

            if ($char !== ':')
            {
                $selec .= $char;
            }
            else
            {
                do
                {
                    $func = null;
                    $data = null;
                    for (++$i; $i < strlen($line); $i++)
                    {
                        $char = $line[$i];
                        if ($char === '(')
                            break;

                        $func .= $char;
                    }

                    if ($line[++$i] !== ')')
                    {
                        for ($i; $i < strlen($line); $i++)
                        {
                            $char = $line[$i];
                            if ($char === ')')
                                break;

                            $data .= $char;
                        }
                    }

                    $funcs[] = ['function' => $func, 'data' => $data];

                }
                while (++$i < strlen($line) && $line[$i] === ':');

                $parts[] = ['selector' => $selec, 'functions' => $funcs];
                $selec = null;
                $funcs = [];
            }
        }

        if (!empty(trim($selec)))
            $parts[] = ['selector' => $selec, 'functions' => $funcs];

        return $parts;

    }

}