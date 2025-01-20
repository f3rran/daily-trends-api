<?php

namespace App\Console\Commands;

use App\Services\scraping\ElMundoScraper as ServicesElMundoScraper;
use App\Services\scraping\ElPaisScraper as ServicesElPaisScraper;
use Illuminate\Console\Command;
use App\Repositories\FeedRepository;

use App\Services\HttpClient\HttpClientInterface;
use App\Services\HtmlParser\HtmlParserInterface;

class ScrapingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scraping-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $feedService;
    protected $httpClient;
    protected $htmlParser;

    public function __construct(FeedRepository $feedService, HttpClientInterface $httpClient, HtmlParserInterface $htmlParser)
    {
        parent::__construct();
        $this->feedService = $feedService;
        $this->httpClient = $httpClient;
        $this->htmlParser = $htmlParser;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scraper = new ServicesElMundoScraper($this->feedService, $this->httpClient, $this->htmlParser);
        $scraper->fetchNews();

        $scraper = new ServicesElPaisScraper($this->feedService);
        $scraper->fetchNews();
    }
}
