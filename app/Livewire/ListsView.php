<?php

namespace App\Livewire;

use Livewire\Component;

class ListsView extends Component
{
    public function render()
    {
        return view('livewire.lists-view')->layout('layouts.app');
    }
}
