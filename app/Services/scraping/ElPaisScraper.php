<?php
namespace App\Services\scraping;

use App\Repositories\FeedRepository;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ElPaisScraper
{
    protected $feedRepository;

    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function fetchNews(): void
    {
        try {
            $response = Http::withOptions([
                'verify' => false, // Ignorar la verificación SSL
            ])->get('https://elpais.com/');

            $crawler = new Crawler($response->body());
        } catch (\Throwable $th) {
            return;
        }


        //Get articles list
        $news = $crawler->filter('article')->each(function (Crawler $node) {
            $link = $node->filter('a[href*="elpais.com"]')->attr('href', true);

            return [
                'url' => $link,
            ];
        });

        // Get the first 5 articles content
        for ($i=0; $i < 5; $i++) {
            $scrapedContent = $this->fetchArticle($news[$i]['url']);

            if ($scrapedContent) {
                $this->feedRepository->store($scrapedContent);
            }
        }

        return;
    }

    private function fetchArticle(string $url):array|null
    {
        try {
            $response = Http::withOptions([
                'verify' => false, // Ignorar la verificación SSL
            ])->get($url);

            $crawler = new Crawler($response->body());
        } catch (\Throwable $th) {
            return null;
        }

        $title = $crawler->filter('h1.a_t')->text('', true);
        $source = "elpais";
        $content = $crawler->filter('div[data-dtm-region="articulo_cuerpo"]')->text('', true);

        return [
            'title' => $title,
            'source' => $source,
            'content' => $content
        ];
    }
}
