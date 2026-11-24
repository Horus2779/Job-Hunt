<?php

namespace App\Services;

use App\Models\ParameterJobs;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JoobleService
{
    protected string $api_key;

    public function __construct()
    {
        $this->api_key = (string) config('services.jooble.key');
    }

    public function getJobs(string $country): array
    {
        if ($this->api_key === '') {
            Log::warning('Jooble: chave de API não configurada no .env');
            return [];
        }

        $cacheKey = "jobs_jooble_$country";

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

        try {
            $response = Http::acceptJson()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                ])
                ->timeout(15)
                ->retry(2, 500, throw: false)
                ->withOptions([
                    'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
                ])
                ->post("https://jooble.org/api/{$this->api_key}", [
                    'keywords' => $keywords,
                    'location' => $location,
                    'page'     => 1,
                ]);
        } catch (ConnectionException $e) {
            Log::warning('Jooble: falha de conexão', ['country' => $country, 'error' => $e->getMessage()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Jooble: erro inesperado', ['country' => $country, 'error' => $e->getMessage()]);
            return null;
        }

        if ($response->failed()) {
            Log::warning('Jooble: resposta com erro', [
                'country' => $country,
                'status'  => $response->status(),
            ]);
            return null;
        }

        return $this->formatResults($response->json('jobs') ?? []);
    }

    private function formatResults(array $results): array
    {
        return collect($results)->map(fn ($job) => [
            'title'       => strip_tags($job['title'] ?? 'N/A'),
            'company'     => $job['company'] ?? 'N/A',
            'location'    => $job['location'] ?? 'Remote',
            'url'         => $job['link'] ?? null,
            'description' => (string) str(strip_tags($job['snippet'] ?? ''))->limit(250),
            'source'      => 'Jooble',
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