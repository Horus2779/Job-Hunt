<?php

namespace App\Services;

use App\Models\ParameterJobs;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdzumaService
{
    protected string $appId;
    protected string $appKey;

    public function __construct()
    {
        $this->appId  = config('services.adzuma.id');
        $this->appKey = config('services.adzuma.key');
    }

    public function getJobs(string $country): array
    {
        $cacheKey = "jobs_adzuna_$country";

        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        $jobs = $this->fetchFromApi($country);

        if ($jobs === null) {
            return [];
        }

        Cache::put($cacheKey, $jobs, 10800);

        return $jobs;
    }

    private function fetchFromApi(string $country): ?array
    {
        $parameters = ParameterJobs::get();

        if ($parameters->isEmpty()) {
            return null;
        }

        $query = $parameters->map(function ($item, $key) use ($parameters) {
            if ($key === $parameters->count() - 1) {
                return $item->parameter;
            }

            return "$item->parameter $item->logic_operator";
        })->implode(' ');

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->retry(2, 500, throw: false)
                ->withOptions([
                    'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
                ])
                ->get("https://api.adzuna.com/v1/api/jobs/{$country}/search/1", [
                    'app_id'           => $this->appId,
                    'app_key'          => $this->appKey,
                    'results_per_page' => 50,
                    'what'             => $query,
                ]);
        } catch (ConnectionException $e) {
            Log::warning('Adzuna: falha de conexão', ['country' => $country, 'error' => $e->getMessage()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Adzuna: erro inesperado', ['country' => $country, 'error' => $e->getMessage()]);
            return null;
        }

        if ($response->failed()) {
            Log::warning('Adzuna: resposta com erro', [
                'country' => $country,
                'status'  => $response->status(),
            ]);
            return null;
        }

        return $this->formatResults($response->json('results') ?? []);
    }

    private function formatResults(array $results): array
    {
        return collect($results)->map(fn ($job) => [
            'title'       => $job['title'] ?? 'N/A',
            'company'     => $job['company']['display_name'] ?? 'N/A',
            'location'    => $job['location']['display_name'] ?? 'Remote',
            'url'         => $job['redirect_url'] ?? null,
            'description' => (string) ($job['description'] ?? ''),
            'source'      => 'Adzuna',
        ])->all();
    }
}