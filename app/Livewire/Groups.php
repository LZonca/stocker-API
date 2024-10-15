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

    public function refreshGroups()
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

        foreach ($this->groups as $group) {
            foreach ($group->stocks as $stock) {
                foreach ($stock->produits as $produit) {
                    if ($produit->userProduits) {
                        $produit->nom = $produit->userProduits->custom_name ?? $produit->nom;
                        $produit->description = $produit->userProduits->custom_description ?? $produit->description;
                        $produit->image = $produit->userProduits->custom_image ?? $produit->image;
                    }
                }
            }
        }
    }

    public function createGroup()
    {
        $newGroup = new Groupe();
        $newGroup->nom = $this->newGroupName;
        $newGroup->proprietaire_id = Auth::user()->id;
        $newGroup->save();

        // Attach the group to the user
        Auth::user()->groupes()->attach($newGroup->id);

        $this->refreshGroups();
        $this->seeCreateModal = false;
        $this->success('Groupe créé avec succès');
    }

    public function render()
    {
        return view('livewire.groups', ['groups' => $this->groups])->layout('layouts.app');
    }
}
