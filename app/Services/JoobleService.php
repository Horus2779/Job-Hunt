<?php

namespace App\Services;

use App\Models\ParameterJobs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class JoobleService
{
    protected string $api_key;

    public function __construct()
    {
        $this->api_key = config('services.jooble.key');
    }

    public function getJobs(string $country) : array
    {
        $cache_key = "jobs_jooble_$country";

        // Ajustado para 10800 (3 horas)
        return Cache::remember($cache_key, 10800, function () use ($country) {
            return $this->fetchFromApi($country);
        });
    }

    private function fetchFromApi(string $country)
    {
        $parameters = ParameterJobs::all();
        
        if ($parameters->isEmpty()) return [];

        $keywords = $parameters->pluck('parameter')->implode(' ');
        $location = $this->mapCountryName($country);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        ])->post("https://jooble.org/api/{$this->api_key}", [
            'keywords' => $keywords,
            'location' => $location,
            'page'     => 1
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $this->formatResults($data['jobs'] ?? []);
        }

        return [];
    }

    private function formatResults(array $results): array
    {
        return collect($results)->map(fn($job) => [
            'title'       => strip_tags($job['title']),
            'company'     => $job['company'] ?? 'N/A',
            'location'    => $job['location'] ?? 'Remote',
            'url'         => $job['link'],
            // O snippet do Jooble pode vir com muito lixo HTML, o strip_tags é essencial aqui
            'description' => str(strip_tags($job['snippet'] ?? ''))->limit(250),
            'source'      => 'Jooble',
        ])->toArray();
    }

    private function mapCountryName(string $code): string
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