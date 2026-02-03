<?php

namespace App\Services;

class JobsService
{
    public function __construct(
       protected AdzumaService $adzuma
    )
    {}

    public function getJobs()
    {

        $jobs = [
            'adzuna' => $this->adzuma->getJobs('br'),
        ];


        return $jobs;
    }
}
