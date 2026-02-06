<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UsersTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $roleFilter = '';
    public array $selected = [];
    public bool $selectAll = false;

    protected $queryString = ['search', 'statusFilter', 'roleFilter'];
    
     public function boot()
    {
        Log::info('Livewire boot', [
            'session_id' => request()->session()->getId(),
            'csrf_token' => csrf_token(),
            'has_csrf_header' => request()->header('X-CSRF-TOKEN') ? 'yes' : 'no',
        ]);
    }
    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedRoleFilter() { $this->resetPage(); }

    public function updatedSelectAll($value)
    {
        $this->selected = $value
            ? $this->users()->pluck('id')->toArray()
            : [];

    }

    public function suspendUser($userId)
    {    
       
        $user = User::findOrFail($userId);

        if ($user->role === 'admin') {
            session()->flash('error', 'Cannot suspend admin users');
            return;
        }

        $user->update([
            'status' => $user->status === 'active' ? 'suspended' : 'active'
        ]);

        session()->flash('message', 'User status updated');
    }

    public function bulkSuspend()
    {
        User::whereIn('id', $this->selected)
            ->where('role', '!=', 'admin')
            ->update(['status' => 'suspended']);

        $this->reset(['selected', 'selectAll']);
        session()->flash('message', 'Users suspended');
    }

    public function deleteUser($userId)
    {
        User::where('id', $userId)
            ->where('role', '!=', 'admin')
            ->delete();

        session()->flash('message', 'User deleted');
    }

    public function users()
    {
        return User::query()
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->when($this->statusFilter, fn ($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->when($this->roleFilter, fn ($q) =>
                $q->where('role', $this->roleFilter)
            )
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.users-table', [
            'users' => $this->users(),
        ]);
    }
}
