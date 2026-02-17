<?php

namespace App\Livewire;

use App\Services\JobsService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AppliedJobs;
use App\Models\ApiSources;

class Home extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    private $perPage = 10;
    public $country = 'br';

    public function render(JobsService $service)
    {
        $allJobs = $service->getJobs($this->country); 
        
        $paginatedJobs = $this->paginateJobs($allJobs);

        return view('livewire.home', $paginatedJobs);
    }

    public function updateCountry()
    {
        $this->resetPage();
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

    public function applyToJob(array $jobData)
    {
        $source = ApiSources::where('name', $jobData['source'])->first();

        AppliedJobs::updateOrCreate(
            ['url' => $jobData['url']] ,
            [
                'title' => $jobData['title'],
                'company' => $jobData['company'],
                'location' => $jobData['location'],
                'source_id' => $source->id ?? 1,
                'status' => 1,
            ]
        );

        session()->flash('message', 'Interesse registrado com sucesso!');
    }
}
