<?php

namespace App\Services;

use App\Models\ParameterJobs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class JSearchService 
{
    protected $apiKey;


    public function __construct()
    {
        $this->apiKey = config('services.jserch.key');
    }

    public function getJobs($country)
    {
        $cacheKey = "jobs_jsearch_$country";

        return Cache::remember($cacheKey, 10800, function () use ($country) {
            return $this->fetchFromApi($country);
        });
    }

    private function fetchFromApi($country)
    {
        $parameters = ParameterJobs::all();

        if($parameters->isEmpty()) return [];

        $keywords = $parameters->pluck('parameter')->implode(' ');
        $location = $this->mapCountryName($country);

        $search = "$keywords in $location";

        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey
        ])->get('https://api.openwebninja.com/api/jsearch/search', [
            'query' => $search,
            'page' => '1',
            'num_pages' => '1',
            'date_posted' => 'all'
        ]);

        dd($response);
    }

    private function mapCountryName($code)
    {
        $map = [
            'br' => 'Brazil',
            'ca' => 'Canada',
            'ie' => 'Ireland',
            'gb' => 'United Kingdom'
        ];

        return $map[strtolower($code)] ?? $code;
    }
}