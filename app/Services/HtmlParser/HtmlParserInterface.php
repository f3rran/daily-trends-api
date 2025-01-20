<?php
namespace App\Services\HtmlParser;

interface HtmlParserInterface
{
    public function createCrawler(string $html): HtmlParserInterface;
    public function filter(string $selector): HtmlParserInterface;
    public function each(callable $callback): array;
    public function attr(string $attribute, bool $safe = false): ?string;
    public function text(string $default = '', bool $safe = false): ?string;
}
