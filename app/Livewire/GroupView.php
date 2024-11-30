<?php

namespace App\Livewire;

use App\Models\Groupe;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class GroupView extends Component
{
    use Toast;

    public $group;
    public bool $seeCreateModal = false;
    public bool $seeEditModal = false;
    public bool $seeDeleteModal = false;
    public $groupStocks = [];
    public string $newGroupStockName = '';
    public string $editGroupStockName = '';
    public int $stockIdToEdit;
    public int $stockIdToDelete;

    public function mount($group): void
    {
        $this->group = Groupe::findOrFail($group);
        $this->refreshGroupStocks();
    }

    public function refreshGroupStocks(): void
    {
        $this->groupStocks = $this->group->stocks()->with('produits')->get();
    }

    public function createStock(): void
    {
        $this->validate([
            'newGroupStockName' => 'required|string|max:255',
            [], [
                'newGroupStockName' => __('Stock name')
            ]
        ]);

        $newStock = new Stock();
        $newStock->nom = $this->newGroupStockName;
        $newStock->proprietaire_id = Auth::user()->id;
        $newStock->groupe_id = $this->group->id;
        $newStock->save();

        $this->refreshGroupStocks();
        $this->seeCreateModal = false;
        $this->success('Stock créé avec succès');

        $this->newGroupStockName = '';
    }

    public function editStock(): void
    {
        $this->validate([
            'editGroupStockName' => 'required|string|max:255',
        ], [
            'editGroupStockName' => __('Stock name')
        ]);

        $stock = Stock::find($this->stockIdToEdit);
        if ($stock) {
            $stock->nom = $this->editGroupStockName;
            $stock->save();
        }

        $this->refreshGroupStocks();
        $this->seeEditModal = false;
        $this->success('Stock modifié avec succès');

        $this->editGroupStockName = '';
    }

    public function confirmEdit($stockId): void
    {
        $this->stockIdToEdit = $stockId;
        $stock = Stock::find($stockId);
        if ($stock) {
            $this->editGroupStockName = $stock->nom;
        }
        $this->seeEditModal = true;
    }

    public function deleteStock(): void
    {
        $stock = Stock::find($this->stockIdToDelete);
        if ($stock) {
            $stock->delete();
        }

        $this->refreshGroupStocks();
        $this->seeDeleteModal = false;
        $this->success('Stock supprimé avec succès');
    }

    public function confirmDelete($stockId): void
    {
        $this->stockIdToDelete = $stockId;
        $this->seeDeleteModal = true;
    }

    public function render()
    {
        return view('livewire.group', ['group' => $this->group])->layout('layouts.app');
    }
}
