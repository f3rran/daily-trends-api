<?php
namespace App\Services\scraping;

use App\Repositories\FeedRepository;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ElMundoScraper
{
    protected $feedRepository;

    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function fetchNews(): void
    {
        $response = Http::get('https://www.elmundo.es/');

        $crawler = new Crawler($response->body());

        //Get articles list
        $news = $crawler->filter('article')->each(function (Crawler $node) {
            $link = $node->filter('a')->attr('href', true);

            return [
                'url' => $link,
            ];
        });

        // Get the first 5 articles content
        for ($i=0; $i < 5; $i++) {
            $this->feedRepository->store($this->fetchArticle($news[$i]['url']));
        }

        return;
    }

    private function fetchArticle(string $url):array
    {
        $response = Http::get($url);

        $crawler = new Crawler($response->body());

        $title = $crawler->filter('h1.ue-c-article__headline')->text('', true);
        $source = "elmundo";
        $content = $crawler->filter('div.ue-c-article__body')->text('', true);

        return [
            'title' => $title,
            'source' => $source,
            'content' => $content
        ];
    }
}
