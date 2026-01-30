<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AdzumaService {

    protected string $appId;
    protected string $appKey;

    public function __construct()
    {
        $this->appId = config('services.adzuma.id');
        $this->appKey = config('services.adzuma.key');
    }

    public function getJobs()
    {
        $country = 'br';
        $query = 'developer';

        $response = Http::get("https://api.adzuna.com/v1/api/jobs/{$country}/search/1", [
                'app_id' => $this->appId,
                'app_key' => $this->appKey,
                'results_per_page' => 10,
                'what' => $query,
                'content-type' => 'application/json'
        ]);

        dd($this->formatResults($response->json()['results']));

    }

    private function formatResults(array $results): array
    {
        return collect($results)->map(fn($job) => [
            'title'       => $job['title'],
            'company'     => $job['company']['display_name'] ?? 'N/A',
            'location'    => $job['location']['display_name'] ?? 'Remote',
            'url'         => $job['redirect_url'],
            'description' => str($job['description'])->limit(150),
            'source'      => 'Adzuna',
        ])->toArray();
    }
}