<?php

namespace Database\Seeders;

use App\Models\AppliedJobs;
use App\Models\ParameterJobs;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Dados fictícios. Atenção: os parâmetros criados aqui disparam buscas reais nas APIs.
     */
    public function run(): void
    {
        $this->call(DatabaseSeeder::class);

        ParameterJobs::factory(3)->create();

        AppliedJobs::factory(15)->create();
    }
}
