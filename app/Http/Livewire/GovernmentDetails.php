<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GovernmentDetails extends Component
{
    public $isGovernment;
    public $test = [];
    public function render()
    {
        return view('livewire.government-details');
    }
}
