<?php

namespace App\Livewire;

use App\Services\JobsService;
use Livewire\Component;

class Home extends Component
{
    public function render(JobsService $service)
    {
        return view('livewire.home', $service->getJobs('br'));
    }

    
}
