<?php

namespace App\Services\HttpClient;


use Illuminate\Support\Facades\Http;



class LaravelHttpClient implements HttpClientInterface
{
    public function get(string $url): string
    {
        return Http::get($url)->body();
    }
}
