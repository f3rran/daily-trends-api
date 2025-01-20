<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HttpClient\LaravelHttpClient;
use App\Services\HtmlParser\DomCrawlerParser;
use App\Services\HtmlParser\HtmlParserInterface;
use App\Services\HttpClient\HttpClientInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HttpClientInterface::class, LaravelHttpClient::class);
        $this->app->bind(HtmlParserInterface::class, DomCrawlerParser::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
