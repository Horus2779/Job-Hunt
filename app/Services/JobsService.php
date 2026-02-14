<?php

namespace App\Services;

class JobsService
{
    public function __construct(
       protected AdzumaService $adzuma,
       protected JoobleService $jooble
    )
    {}

    public function getJobs()
    {

        $jobs = [
            'adzuna' => $this->adzuma->getJobs('br'),
            'jooble' => $this->jooble->getJobs('br'),
        ];

        return $jobs;
    }
}
