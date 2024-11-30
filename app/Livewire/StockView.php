<?php

namespace App\Livewire;

use App\Models\Produit;
use App\Models\Stock;
use Livewire\Component;
use Mary\Traits\Toast;

class StockView extends Component
{
    use Toast;
    public $stock;
    public $products = [];
    public $userProduits = [];

    public $newProductName = '';
    public $newProductDescription = '';
    public $newProductCode = '';
    public $newProductPrice = '';
    public $newProductQuantity = '';
    public $newProductExpiryDate = '';

    public bool $seeCreateModal;

    public function mount($stock)
    {
        $this->seeCreateModal = false;
        $this->stock = Stock::findOrFail($stock);
        $this->products = $this->stock->produits;
    }

    public function refreshProducts()
    {
        $this->products = $this->stock->produits;
    }

    public function createProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255|unique:produits,nom,NULL,id,stock_id,' . $this->stock->id,
            'newProductDescription' => 'nullable|string',
            'newProductCode' => 'nullable|unique:produits,code|max:255',
            'newProductPrice' => 'nullable|numeric|min:0',
            'newProductQuantity' => 'nullable|integer|min:0',
            'newProductExpiryDate' => 'nullable|date',
        ], [], [
            'newProductName' => __('Product name'),
            'newProductDescription' => __('Product description'),
            'newProductCode' => __('Product code'),
            'newProductPrice' => __('Product price'),
            'newProductQuantity' => __('Product quantity'),
            'newProductExpiryDate' => __('Product expiry date'),
        ]);

        $newProduit = new Produit();
        $newProduit->stock_id = $this->stock->id;
        $newProduit->nom = $this->newProductName;
        $newProduit->description = $this->newProductDescription;
        $newProduit->code = $this->newProductCode;
        $newProduit->prix = $this->newProductPrice ?: null; // Set to null if empty
        $newProduit->quantite = $this->newProductQuantity ?: 0;
        $newProduit->expiry_date = $this->newProductExpiryDate ?: null; // Set to null if empty
        $newProduit->save();

        $this->refreshProducts();
        $this->seeCreateModal = false;
        $this->success('Product created successfully');

        $this->resetForm();
    }

    private function resetForm()
    {
        $this->newProductName = '';
        $this->newProductDescription = '';
        $this->newProductCode = '';
        $this->newProductPrice = '';
        $this->newProductQuantity = '';
        $this->newProductExpiryDate = '';
    }

    private function toggleArchive($product)
    {
        $product->archived = !$product->archived;
        $product->save();
        $this->refreshProducts();
    }

    public function render()
    {
        return view('livewire.stock', [
            'stock' => $this->stock,
            'products' => $this->products,
        ])->layout('layouts.app');
    }
}
