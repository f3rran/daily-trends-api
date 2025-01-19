<?php

namespace App\Console\Commands;

use App\Services\scraping\ElMundoScraper as ServicesElMundoScraper;
use App\Services\scraping\ElPaisScraper as ServicesElPaisScraper;
use Illuminate\Console\Command;
use App\Repositories\FeedRepository;

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

    public function __construct(FeedRepository $feedService)
    {
        parent::__construct();
        $this->feedService = $feedService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scraper = new ServicesElMundoScraper($this->feedService);
        $scraper->fetchNews();

        $scraper = new ServicesElPaisScraper($this->feedService);
        $scraper->fetchNews();
    }
}
