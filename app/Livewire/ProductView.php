<?php

namespace App\Livewire;

use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductView extends Component
{
    public $product;

    public function mount($product)
    {
        $this->product = Produit::findOrFail($product);
    }

    public function render()
    {
        $product = $this->product;
        return view('livewire.produit', compact('product'))->layout('layouts.app');
    }
}
