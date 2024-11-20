<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
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
    public function mount(): void
    {
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1 text-gray-200'],
            ['key' => 'created_at', 'label' => 'Date', 'class' => 'text-gray-200'],
            ['key' => 'log_name', 'label' => __('Subject'), 'class' => 'text-gray-200'],
            ['key' => 'description', 'label' =>__( 'Description'), 'class' => 'text-gray-200'],
            ['key' => 'subject_type', 'label' => __('Category'), 'class' => 'text-gray-200'],
            ['key' => 'causer_id', 'label' => __('User'), 'class' => 'text-center text-gray-200'],
            ['key' => 'properties', 'label' => __('Properties'), 'class' => 'w-1 text-gray-200', 'sortable' => false]
        ];
    }

    public function showLogDetails($logId): void
    {
        $this->selectedLog = Activity::find($logId);
        $this->seeModal = true;
    }

    public function sortBy($column): void
    {
        if ($this->sortBy['column'] === $column) {
            $this->sortBy['direction'] = $this->sortBy['direction'] === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy['column'] = $column;
            $this->sortBy['direction'] = 'asc';
        }
    }

    public function refreshLogs(): void
    {
        $this->dispatch('refreshedLogs');
    }

    public function render(): Application|Factory|View|\Illuminate\View\View
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
            ->where(function ($query) {
                $searchTerm = strtolower($this->searchTerm);
                $query->whereRaw('LOWER(log_name) like ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(description) like ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(subject_type) like ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(causer_id) like ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(properties) like ?', ["%{$searchTerm}%"]);
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->latest()
            ->paginate(5);

        return view('livewire.logs-view', compact('logs'))->layout('layouts.app');
    }
}
