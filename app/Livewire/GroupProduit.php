<?php

namespace App\Livewire;

use App\Models\Produit;
use App\Models\UserProduit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GroupProduit extends Component
{
    public $product;
    public $userProduit;

    public function mount($product)
    {
        $this->product = Produit::findOrFail($product);
        $this->userProduit = UserProduit::where('user_id', Auth::id())
            ->where('produit_id', $this->product->id)
            ->first();
    }

    public function render()
    {
        $product = $this->userProduit ?? $this->product;
        return view('livewire.group-produit', compact('product'))->layout('layouts.app');
    }
}
