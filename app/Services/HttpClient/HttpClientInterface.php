<?php


namespace App\Services\HttpClient;

interface HttpClientInterface
{
     public function get(string $url):  string;
}
