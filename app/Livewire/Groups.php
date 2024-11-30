<?php

namespace App\Livewire;

use App\Models\Groupe;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class Groups extends Component
{
    use Toast;

    public $groups = [];
    public bool $seeCreateModal = false;
    public bool $seeEditModal = false;
    public bool $seeDeleteModal = false;
    public string $newGroupName = '';
    public string $editGroupName = '';
    public int $groupIdToEdit;
    public int $groupIdToDelete;
    public bool $seeMembersModal = false;
    public $groupMembers = [];

    public function mount()
    {
        $this->refreshGroups();
    }

    public function refreshGroups(): void
    {
        $this->groups = Auth::user()->groupes()->with([
            'proprietaire',
            'members',
            'stocks.produits'
        ])->get();
    }

    public function createGroup(): void
    {
        $this->validate([
            'newGroupName' => 'required|string|max:255'],
            [], [
                'editGroupName' => __('Stock name')
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

    public function editGroup(): void
    {
        $this->validate([
            'editGroupName' => 'required|string|max:255',
        ], [
            'editGroupName' => __('Stock name')
        ]);

        $group = Groupe::find($this->groupIdToEdit);
        if ($group) {
            $group->nom = $this->editGroupName;
            $group->save();
        }

        $this->refreshGroups();
        $this->seeEditModal = false;
        $this->success('Groupe modifié avec succès');

        $this->editGroupName = '';
    }

    public function confirmEdit($groupId): void
    {
        $this->groupIdToEdit = $groupId;
        $group = Groupe::find($groupId);
        if ($group) {
            $this->editGroupName = $group->nom;
        }
        $this->seeEditModal = true;
    }

    public function deleteGroup(): void
    {
        $group = Groupe::find($this->groupIdToDelete);
        if ($group) {
            $group->delete();
        }

        $this->refreshGroups();
        $this->seeDeleteModal = false;
        $this->success('Groupe supprimé avec succès');
    }

    public function showMembers($groupId): void
    {
        $group = Groupe::find($groupId);
        if ($group) {
            $this->groupMembers = $group->members;
        }
        $this->seeMembersModal = true;
    }

    public function confirmDelete($groupId): void
    {
        $this->groupIdToDelete = $groupId;
        $this->seeDeleteModal = true;
    }

    public function render()
    {
        return view('livewire.groups', ['groups' => $this->groups])->layout('layouts.app');
    }
}
