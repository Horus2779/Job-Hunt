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
        $this->apiKey = config('services.jsearch.key');
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
            'x-api-key' => $this->apiKey,
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])->get('https://api.openwebninja.com/jsearch/search', [
            'query' => $search,
            'page' => '1',
            'num_pages' => '5',
            'date_posted' => 'all'
        ]);

        if ($response->successful()) {
            return $this->formatResults($response->json()['data'] ?? []);
        }

        return [];
    }

    private function formatResults(array $results): array
    {
        return collect($results)->map(fn($job) => [
            'title'       => $job['job_title'] ?? 'N/A',
            'company'     => $job['employer_name'] ?? 'N/A',
            'location'    => ($job['job_city'] ?? '') . ' ' . ($job['job_country'] ?? ''),
            'url'         => $job['job_apply_link'] ?? ($job['job_google_link'] ?? '#'),
            'description' => str($job['job_description'] ?? '')->limit(250),
            'source'      => 'JSearch',
            'logo'        => $job['employer_logo'] ?? null, // Bônus: Logo da empresa!
        ])->toArray();
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