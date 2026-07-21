<?php

namespace App\Livewire\Admin;

use App\Models\Registrar;
use App\Models\RegistrarApiLog;
use Livewire\Component;
use Livewire\WithPagination;

class RegistrarLogsComponent extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedRegistrar = '';
    public string $selectedStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearLogs(): void
    {
        RegistrarApiLog::truncate();
    }

    public function render()
    {
        $query = RegistrarApiLog::with('registrar')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('action', 'like', '%' . $this->search . '%')
                  ->orWhere('endpoint', 'like', '%' . $this->search . '%')
                  ->orWhere('error', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->selectedRegistrar)) {
            $query->where('registrar_id', $this->selectedRegistrar);
        }

        if ($this->selectedStatus === '200') {
            $query->where('http_status', 200);
        } elseif ($this->selectedStatus === 'error') {
            $query->where('http_status', '!=', 200);
        }

        $logs = $query->paginate(15);
        $registrars = Registrar::all();

        return view('livewire.admin.registrar-logs-component', compact('logs', 'registrars'));
    }
}
