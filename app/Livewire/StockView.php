<?php

namespace App\Livewire;

use App\Models\Produit;
use App\Models\UserProduit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Stock;
use Mary\Traits\Toast;

class StockView extends Component
{
    use Toast;
    public $stock;
    public $products = [];
    public $userProduits = [];

    public $newProductName ='';
    public $newProductDescription ='';
    public $newProductCode ='';

    public bool $seeCreateModal;

    public function mount($stock)
    {
        $this->seeCreateModal = false;
        $this->stock = Stock::findOrFail($stock);
        $this->products = $this->stock->produits;

        // Fetch UserProduits for the current user and products in the stock
        $this->userProduits = UserProduit::where('user_id', Auth::id())
            ->whereIn('produit_id', $this->products->pluck('id'))
            ->get()
            ->keyBy('produit_id');
    }

    public function createProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
            'newProductDescription' => 'nullable|string',
            'newProductCode' => 'nullable|unique:produits,code|max:255',
        ]);

        $newProduit = new Produit();
        $newProduit->nom = $this->newProductName;
        $newProduit->description = $this->newProductDescription;
        $newProduit->code = $this->newProductCode;
        $newProduit->save();

        $this->stock->produits()->attach($newProduit->id);

        // Create a UserProduit entry
        $userProduit = new UserProduit();
        $userProduit->user_id = Auth::id();
        $userProduit->produit_id = $newProduit->id;
        $userProduit->custom_name = $this->newProductName;
        $userProduit->custom_description = $this->newProductDescription;
        $userProduit->save();

        $this->products = $this->stock->produits;
        $this->seeCreateModal = false;
        $this->success('ProductView créé avec succès');

        $this->newProductName = '';
        $this->newProductDescription = '';
        $this->newProductCode = '';

        // Refresh UserProduits
        $this->userProduits = UserProduit::where('user_id', Auth::id())
            ->whereIn('produit_id', $this->products->pluck('id'))
            ->get()
            ->keyBy('produit_id');
    }

    public function render()
    {
        return view('livewire.stock', [
            'stock' => $this->stock,
            'products' => $this->products,
            'userProduits' => $this->userProduits,
        ])->layout('layouts.app');
    }
}
