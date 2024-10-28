<?php

namespace App\Livewire;

namespace App\Livewire;

use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Groupe;
use Mary\Traits\Toast;

class GroupView extends Component
{
    use Toast;

    public $group;
    public bool $seeCreateModal = false;
    public $groupStocks = [];
    public string $newGroupStockName = '';

    public function mount($group): void
    {
        $this->group = Groupe::findOrFail($group);
        $this->groupStocks = $this->group->stocks()->with('produits')->get();
    }

    public function refreshGroupStocks(): void
    {
        $this->groupStocks = $this->group->stocks()->with('produits')->get();
    }

    public function createStock(): void
    {
        $this->validate([
            'newGroupStockName' => 'required|string|max:255',
        ]);


        $newStock = new Stock();
        $newStock->nom = $this->newGroupStockName;
        $newStock->proprietaire_id = Auth::user()->id;
        $newStock->groupe_id = $this->group->id; // Associate the stock with the group
        $newStock->save();

        $newStock->groupe()->associate($this->group->id);

        $this->refreshGroupStocks();
        $this->seeCreateModal = false;
        $this->success('Stock créé avec succès');

        $this->newGroupStockName = '';
    }

    public function render()
    {
        return view('livewire.group', ['group' => $this->group])->layout('layouts.app');
    }
}
