<?php

namespace App\Livewire;

use AllowDynamicProperties;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

#[AllowDynamicProperties] class Stocks extends Component
{
    use Toast;

    public $stocks = [];
    public bool $seeCreateModal = false;
    public bool $seeEditModal = false;
    public bool $seeDeleteModal = false;
    public string $newStockName = '';
    public int $stockIdToEdit;
    public string $editStockName = '';

    public function mount(): void
    {
        $this->seeCreateModal = false;
        $this->refreshStocks();
    }

    public function refreshStocks(): void
    {
        $this->stocks = Auth::user()->stocks()->with('produits')->get();
    }

    public function deleteStock(): void
    {
        $stock = Stock::find($this->stockIdToDelete);
        if ($stock) {
            $stock->delete();
        }
        $this->error('Stock supprimé', 'Le stock a été supprimé avec succès', 'toast-top', 'o-trash');

        $this->seeDeleteModal = false;
        $this->refreshStocks();
    }

    public function confirmDelete($stockId): void
    {
        $this->stockIdToDelete = $stockId;
        $this->seeDeleteModal = true;
    }

    public function createStock(): void
    {
        $this->validate([
            'newStockName' => 'required|string|max:255',
        ], [], [
            'newStockName' => __('Stock name')
        ]);

        $newStock = new Stock();
        $newStock->nom = $this->newStockName;
        $newStock->proprietaire_id = Auth::user()->id;
        $newStock->save();

        $this->refreshStocks();
        $this->seeCreateModal = false;
        $this->success('Stock créé avec succès');

        $this->newStockName = '';
    }

    public function editStock(): void
    {
        $this->validate([
            'editStockName' => 'required|string|max:255',
        ], [], [
            'editStockName' => __('Stock name')
        ]);

        $stock = Stock::find($this->stockIdToEdit);
        if ($stock) {
            $stock->nom = $this->editStockName;
            $stock->save();
        }

        $this->refreshStocks();
        $this->seeEditModal = false;
        $this->success('Stock modifié avec succès');

        $this->editStockName = '';
    }

    public function confirmEdit($stockId): void
    {
        $this->stockIdToEdit = $stockId;
        $stock = Stock::find($stockId);
        if ($stock) {
            $this->editStockName = $stock->nom;
        }
        $this->seeEditModal = true;
    }

    public function render()
    {
        return view('livewire.stocks')->layout('layouts.app');
    }
}
