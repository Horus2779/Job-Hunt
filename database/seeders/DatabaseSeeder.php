<?php

namespace Database\Seeders;

use App\Models\ApiSources;
use App\Models\AppliedStatus;
use App\Models\LocationJobs;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Dados de referência reais (idempotente: pode rodar mais de uma vez).
     * Para dados fictícios de demonstração: php artisan db:seed --class=DemoSeeder
     */
    public function run(): void
    {
        foreach (['Adzuna', 'Jooble', 'JSearch'] as $name) {
            ApiSources::firstOrCreate(['name' => $name]);
        }

        foreach (['Applied', 'Refused', 'Accepted'] as $status) {
            AppliedStatus::firstOrCreate(['status' => $status]);
        }

        foreach (['br', 'ca', 'gb', 'ie'] as $country) {
            LocationJobs::firstOrCreate(['country' => $country]);
        }
    }
}
