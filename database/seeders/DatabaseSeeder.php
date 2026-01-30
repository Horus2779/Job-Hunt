<?php

namespace Database\Seeders;

use App\Models\ApiSources;
use App\Models\AppliedJobs;
use App\Models\AppliedStatus;
use App\Models\LocationJobs;
use App\Models\ParameterJobs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ApiSources::factory(4)->create();

        AppliedStatus::factory(3)->create();

        LocationJobs::factory(3)->create();

        ParameterJobs::factory(3)->create();

        AppliedJobs::factory(15)->create();
        
    }
}
