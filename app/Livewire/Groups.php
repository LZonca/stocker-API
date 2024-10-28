<?php

namespace App\Livewire;

use App\Models\Groupe;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

class Groups extends Component
{
    Use Toast;
    public $groups = [];
    public bool $seeCreateModal;
    public string $newGroupName;

    public function mount()
    {
        $this->seeCreateModal = false;
        $this->refreshGroups();
    }

    public function refreshGroups(): void
    {
        $this->groups = Auth::user()->groupes()->with([
            'proprietaire',
            'members',
            'stocks.produits' => function ($query) {
                $query->with(['userProduits' => function ($query) {
                    $query->where('user_id', Auth::id());
                }]);
            },
        ])->get();
    }

    public function createGroup(): void
    {
        $this->validate([
            'newGroupName' => 'required|string|max:255',
        ]);

        $newGroup = new Groupe();
        $newGroup->nom = $this->newGroupName;
        $newGroup->proprietaire_id = Auth::user()->id;
        $newGroup->save();

        Auth::user()->groupes()->attach($newGroup->id);

        $this->refreshGroups();
        $this->seeCreateModal = false;
        $this->success('Groupe créé avec succès');

        $this->newGroupName = '';
    }

    public function render()
    {
        return view('livewire.groups', ['groups' => $this->groups])->layout('layouts.app');
    }
}
