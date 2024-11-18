<?php

namespace App\Livewire;

use App\Models\Groupe;
use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class GroupStock extends Component
{

    use Toast;
    public $stock;
    public $groupe;
    public $products = [];
    public $newProductName ='';
    public $newProductDescription ='';
    public $newProductCode ='';

    public bool $seeCreateModal;

    public function mount($stock, $groupe): void
    {
        $this->seeCreateModal = false;
        $this->stock = Stock::findOrFail($stock);
        $this->groupe = Groupe::findOrFail($groupe);
        $this->products = $this->stock->produits;
    }

    public function createProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
            'newProductDescription' => 'nullable|string',
            'newProductCode' => 'nullable|unique:produits,code|max:255',
        ]);

        $newProduit = new Produit();
        $newProduit->stock_id = $this->stock->id;
        $newProduit->nom = $this->newProductName;
        $newProduit->description = $this->newProductDescription;
        $newProduit->code = $this->newProductCode;
        $newProduit->quantite = 1;
        $newProduit->save();

        // Create a UserProduit entry

        $this->products = $this->stock->produits;
        $this->seeCreateModal = false;
        $this->success('ProductView créé avec succès');

        $this->newProductName = '';
        $this->newProductDescription = '';
        $this->newProductCode = '';
    }

    public function render()
    {
        return view('livewire.group-stock', [
            'stock' => $this->stock,
            'products' => $this->products,
        ])->layout('layouts.app');
    }
}
