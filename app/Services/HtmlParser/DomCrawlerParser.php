<?php
namespace App\Services\HtmlParser;

use Symfony\Component\DomCrawler\Crawler;

class DomCrawlerParser implements HtmlParserInterface
{
    protected $crawler;

    public function createCrawler(string $html): HtmlParserInterface
    {
        $this->crawler = new Crawler($html);
        return $this;
    }

    public function filter(string $selector): HtmlParserInterface
    {
        $this->crawler = $this->crawler->filter($selector);
        return $this;
    }

    public function each(callable $callback): array
    {
        return $this->crawler->each(function ($node) use ($callback) {
            return $callback($node);
        });
    }

    public function attr(string $attribute, bool $safe = false): ?string
    {
        return $this->crawler->attr($attribute) ?? ($safe ? null : '');
    }

    public function text(string $default = '', bool $safe = false): ?string
    {
        try {
            return $this->crawler->text() ?? ($safe ? null : $default);
        } catch (\Throwable $th) {
            //throw $th;
            return '';
        }
    }
}
