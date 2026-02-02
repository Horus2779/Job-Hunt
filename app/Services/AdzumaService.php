<?php

namespace App\Services;

use App\Models\ParameterJobs;
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
        $countries = ['br', 'ca', 'gb'];
        $parameters = ParameterJobs::get();

        $query = $parameters->map( function ($item, $key) use ($parameters) {
            if($key === $parameters->count() - 1) {
                return $item->parameter;
            }

            return "$item->parameter $item->logic_operator";
        })->implode(' ');

        foreach($countries as $country) {
            $response = Http::get("https://api.adzuna.com/v1/api/jobs/{$country}/search/1", [
                'app_id' => $this->appId,
                'app_key' => $this->appKey,
                'results_per_page' => 50,
                'what' => $query,
                'content-type' => 'application/json'
            ]);

            $data[$country] = $this->formatResults($response->json()['results']);
        }

        dd($data);

    }

    private function formatResults(array $results): array
    {
        return collect($results)->map(fn($job) => [
            'title'       => $job['title'],
            'company'     => $job['company']['display_name'] ?? 'N/A',
            'location'    => $job['location']['display_name'] ?? 'Remote',
            'url'         => $job['redirect_url'],
            'description' => str($job['description']),
            'source'      => 'Adzuna',
        ])->toArray();
    }
}