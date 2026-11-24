<?php

namespace App\Services;

use App\Models\ParameterJobs;
use Illuminate\Database\Eloquent\Collection;
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
        $this->appId  = (string) config('services.adzuma.id');
        $this->appKey = (string) config('services.adzuma.key');
    }

    public function getJobs(string $country): array
    {
        if ($this->appId === '' || $this->appKey === '') {
            Log::warning('Adzuna: chave de API não configurada no .env');
            return [];
        }

        $cacheKey = "jobs_adzuna_$country";

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
        $whatAnd = $parameters->where('logic_operator', 'AND')->pluck('parameter')->implode(' ');
        $whatOr  = $parameters->where('logic_operator', '!=', 'AND')->pluck('parameter')->implode(' ');

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
                ] + array_filter([
                    'what_and' => $whatAnd,
                    'what_or'  => $whatOr,
                ]));
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