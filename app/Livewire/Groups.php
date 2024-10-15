<?php

namespace App\Livewire;

use App\Models\Groupe;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Groups extends Component
{
    public $groups = [];

    public function mount()
    {
        $this->groups = Auth::user()->groupes()->get();
    }

    public function render()
    {
        return view('livewire.groups')->layout('layouts.app');
    }
}
