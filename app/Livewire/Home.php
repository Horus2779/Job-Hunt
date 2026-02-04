<?php

namespace App\Livewire;

use App\Services\JobsService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Home extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    private $perPage = 10;

    public function render(JobsService $service)
    {
        $allJobs = $service->getJobs(); 
        
        $paginatedJobs = $this->paginateJobs($allJobs);

        return view('livewire.home', $paginatedJobs);
    }

    private function paginateJobs($jobs)
    {
        foreach ($jobs as $source => $items) {
            $currentPage = $this->paginators[$source] ?? 1;
            $currentItems = collect($items)->slice(($currentPage - 1) * $this->perPage, $this->perPage)->all();

            $paginatedJobs[$source] = new LengthAwarePaginator(
                $currentItems,
                count($items),
                $this->perPage,
                $currentPage,
                [
                    'path' => url()->current(),
                    'pageName' => $source
                ]
            );
        }

        return $paginatedJobs;
    }
}
