<?php
namespace App\Services\Scraping;

use App\Repositories\FeedRepository;
use App\Services\HttpClient\HttpClientInterface;
use App\Services\HtmlParser\HtmlParserInterface;
use App\Services\Sanitizer\ScrapingSanitizer;

class ElMundoScraper
{
    protected $feedRepository;
    protected $httpClient;
    protected $htmlParser;

    public function __construct(
        FeedRepository $feedRepository,
        HttpClientInterface $httpClient,
        HtmlParserInterface $htmlParser
    ) {
        $this->feedRepository = $feedRepository;
        $this->httpClient = $httpClient;
        $this->htmlParser = $htmlParser;
    }

    public function fetchNews(): void
    {
        try {
            $response = $this->httpClient->get('https://www.elmundo.es/');
            $crawler = $this->htmlParser->createCrawler($response);
        } catch (\Throwable $th) {
            return;
        }

        // Get articles list
        $news = $crawler->filter('article')->each(function ($node) {
            $link = $node->filter('a')->attr('href', true);

            return ['url' => $link];
        });

        // Get the first 5 articles content
        foreach (array_slice($news, 0, 5) as $article) {
            $scrapedContent = $this->fetchArticle($article['url']);
            if ($scrapedContent) {
                $this->feedRepository->store($scrapedContent);
            }
        }
    }

    private function fetchArticle(string $url): ?array
    {
        try {
            $response = $this->httpClient->get($url);
            $crawler = $this->htmlParser->createCrawler($response);
        } catch (\Throwable $th) {
            return null;
        }

        $title = ScrapingSanitizer::sanitizeText($crawler->filter('h1.ue-c-article__headline')->text('', true));
        $source = "elmundo";
        $content = ScrapingSanitizer::sanitizeText($crawler->filter('div.ue-c-article__body')->text('', true));

        return [
            'title' => $title,
            'source' => $source,
            'content' => $content
        ];
    }
}
