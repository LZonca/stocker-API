<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class LogsView extends Component
{
    use WithPagination;
    public $searchTerm;
    public $headers;
    public $selectedLog;
    public bool $seeModal = false;
    public array $selected = [];
    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    #[On('refreshedLogs')]
    public function mount()
    {
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1 text-gray-200'],
            ['key' => 'created_at', 'label' => 'Date', 'class' => 'text-gray-200'],
            ['key' => 'log_name', 'label' => 'Sujet', 'class' => 'text-gray-200'],
            ['key' => 'description', 'label' => 'Description', 'class' => 'text-gray-200'],
            ['key' => 'subject_type', 'label' => 'Modèle affecté', 'class' => 'text-gray-200'],
            ['key' => 'causer_id', 'label' => 'Responsable', 'class' => 'text-center text-gray-200'],
            ['key' => 'properties', 'label' => 'Propriétés', 'class' => 'w-1 text-gray-200', 'sortable' => false]
        ];
    }

    public function showLogDetails($logId)
    {
        $this->selectedLog = Activity::find($logId);
        $this->seeModal = true;
    }

    public function sortBy($column)
    {
        if ($this->sortBy['column'] === $column) {
            $this->sortBy['direction'] = $this->sortBy['direction'] === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy['column'] = $column;
            $this->sortBy['direction'] = 'asc';
        }
    }

    public function render()
    {
        $user = auth()->user();

        $logs = Activity::where(function ($query) use ($user) {
            $query->where('causer_id', $user->id)
                ->orWhere(function ($query) use ($user) {
                    if ($user->produits) {
                        $query->where('subject_type', 'App\Models\Produit')
                            ->whereIn('subject_id', $user->produits->pluck('id'));
                    }
                })
                ->orWhere(function ($query) use ($user) {
                    if ($user->stocks) {
                        $query->where('subject_type', 'App\Models\Stock')
                            ->whereIn('subject_id', $user->stocks->pluck('id'));
                    }
                })
                ->orWhere(function ($query) use ($user) {
                    if ($user->groupes) {
                        $query->where('subject_type', 'App\Models\Groupe')
                            ->whereIn('subject_id', $user->groupes->where('owner_id', $user->id)->pluck('id'));
                    }
                });
        })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->latest()
            ->paginate(5);

        return view('livewire.logs-view', compact('logs'))->layout('layouts.app');
    }
}
