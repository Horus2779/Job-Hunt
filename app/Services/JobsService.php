<?php

namespace App\Services;

class JobsService
{
    public function __construct(
       protected AdzumaService $adzuma,
       protected JoobleService $jooble
    )
    {}

    public function getJobs($country)
    {

        $jobs = [
            'adzuna' => $this->adzuma->getJobs($country),
            'jooble' => $this->jooble->getJobs($country),
        ];

        return $jobs;
    }
}
