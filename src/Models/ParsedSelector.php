<?php

namespace Jerodev\Diglett\Models;

use Jerodev\Diglett\CssFilters\ICssFilter;

class ParsedSelector
{
    /** @var null|string */
    private $selector;

    /** @var ICssFilter[] */
    private $functions;

    /**
     *  @param null|string $selector
     *  @param ICssFilter[] $functions
     */
    public function __construct(?string $selector = null, array $functions = [])
    {
        $this->selector = $selector;
        $this->functions = $functions;
    }

    /**
     *  @return null|string;
     */
    public function getSelector(): ?string
    {
        return $this->selector;
    }

    /**
     *  @return ICssFilter[]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     *  Return the function at a specific index.
     *
     *  @param int $index
     *
     *  @return ICssFilter|null
     */
    public function getFunction(int $index = 0): ?ICssFilter
    {
        return $this->functions[$index] ?? null;
    }

    /**
     *  Get the number of functions.
     *
     *  @return int
     */
    public function getFunctionCount(): int
    {
        return count($this->functions);
    }
}
