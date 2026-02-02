<?php

namespace App\Livewire;

use App\Models\ParameterJobs;
use Livewire\Component;

class JobParameter extends Component
{
    public $parameter = '';
    public $logic = '';

    public function render()
    {
        return view('livewire.job-parameter', [
            'parameters' =>ParameterJobs::all()
        ]);
    }

    public function update()
    {
        $this->validate([
            'parameter' => ['required', 'min:3'],
            'logic' => ['required', 'in:AND,OR'],
        ]);

        ParameterJobs::create([
            'parameter' => $this->parameter,
            'logic_operator' => $this->logic
        ]);

        $this->reset(['parameter', 'logic']);
    }

    public function delete($id)
    {
        $parameter = ParameterJobs::findOrFail($id);
        $parameter->delete();
    }
}
