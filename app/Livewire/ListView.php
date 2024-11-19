<?php

namespace App\Livewire;

use Livewire\Component;

class ListView extends Component
{
    public function render()
    {
        return view('livewire.list-view')->layout('layouts.app');
    }
}
