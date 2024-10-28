<?php

namespace App\Livewire;

use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class Stocks extends Component
{

    Use Toast;
    public $stocks = [];
    public bool $seeCreateModal;
    public string $newStockName;

    public function mount(): void
    {
        $this->seeCreateModal = false;
        $this->refreshStocks();
    }

    public function refreshStocks(): void
    {
        $this->stocks = Auth::user()->stocks()->with([
            'produits' => function ($query) {
                $query->with(['userProduits' => function ($query) {
                    $query->where('user_id', Auth::id());
                }]);
            },
        ])->get();
    }

    public function createStock(): void
    {
        $this->validate([
            'newStockName' => 'required|string|max:255',
        ]);

        $newStock = new Stock();
        $newStock->nom = $this->newStockName;
        $newStock->proprietaire_id = Auth::user()->id;
        $newStock->save();

        $newStock->proprietaire()->associate(Auth::user());

        $this->refreshStocks();
        $this->seeCreateModal = false;
        $this->success('Stock créé avec succès');

        $this->newStockName = '';
    }
    public function render()
    {
        return view('livewire.stocks')->layout('layouts.app');
    }
}
