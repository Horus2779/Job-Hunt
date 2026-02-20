<?php

namespace App\Services;

class JobsService
{
    public function __construct(
       protected AdzumaService $adzuma,
       protected JoobleService $jooble,
       protected JSearchService $jsearch
    )
    {}

    public function getJobs($country)
    {

        $jobs = [
            'adzuna' => $this->adzuma->getJobs($country),
            'jooble' => $this->jooble->getJobs($country),
            'jsearch' => $this->jsearch->getJobs($country)
        ];

        return $jobs;
    }
}
