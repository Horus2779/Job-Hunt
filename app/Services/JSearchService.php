<?php

namespace App\Services;

use App\Models\ParameterJobs;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JSearchService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = (string) config('services.jsearch.key');
    }

    public function getJobs(string $country): array
    {
        if ($this->apiKey === '') {
            Log::warning('JSearch: chave de API não configurada no .env');
            return [];
        }

        $cacheKey = "jobs_jsearch_$country";

        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        $parameters = ParameterJobs::all();

        if ($parameters->isEmpty()) {
            return [];
        }

        $jobs = $this->fetchFromApi($country, $parameters);

        if ($jobs === null) {
            Cache::put($cacheKey, [], 300);
            return [];
        }

        Cache::put($cacheKey, $jobs, 10800);

        return $jobs;
    }

    private function fetchFromApi(string $country, Collection $parameters): ?array
    {
        $keywords = $parameters->pluck('parameter')->implode(' ');
        $location = $this->mapCountryName($country);

        $search = "$keywords in $location";

        try {
            $response = Http::acceptJson()
                ->withHeaders([
                    'x-api-key'  => $this->apiKey,
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->timeout(30)
                ->retry(2, 500, throw: false)
                ->withOptions([
                    'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
                ])
                ->get('https://api.openwebninja.com/jsearch/search', [
                    'query'       => $search,
                    'page'        => '1',
                    'num_pages'   => (string) config('services.jsearch.pages', 2),
                    'date_posted' => 'all',
                ]);
        } catch (ConnectionException $e) {
            Log::warning('JSearch: falha de conexão', ['country' => $country]);
            return null;
        } catch (\Throwable $e) {
            Log::error('JSearch: erro inesperado', ['country' => $country, 'class' => $e::class]);
            return null;
        }

        if ($response->failed()) {
            Log::warning('JSearch: resposta com erro', [
                'country' => $country,
                'status'  => $response->status(),
            ]);
            return null;
        }

        return $this->formatResults($response->json('data') ?? []);
    }

    private function formatResults(array $results): array
    {
        return collect($results)->map(fn ($job) => [
            'title'       => $job['job_title'] ?? 'N/A',
            'company'     => $job['employer_name'] ?? 'N/A',
            'location'    => trim(($job['job_city'] ?? '') . ' ' . ($job['job_country'] ?? '')) ?: 'Remote',
            'url'         => $job['job_apply_link'] ?? ($job['job_google_link'] ?? '#'),
            'description' => (string) str($job['job_description'] ?? '')->limit(250),
            'source'      => 'JSearch',
            'logo'        => $job['employer_logo'] ?? null,
        ])->all();
    }

    private function mapCountryName(string $code): string
    {
        $map = [
            'br' => 'Brazil',
            'ca' => 'Canada',
            'ie' => 'Ireland',
            'gb' => 'United Kingdom',
        ];

        return $map[strtolower($code)] ?? $code;
    }
}