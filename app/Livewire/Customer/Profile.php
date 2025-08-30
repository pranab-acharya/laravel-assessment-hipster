<?php

namespace App\Livewire\Customer;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profile')]
class Profile extends Component
{
    #[Rule('required|string|min:2|max:100')]
    public string $name = '';

    #[Rule('required|email')]
    public string $email = '';

    public function mount(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->name = (string) $user->name;
            $this->email = (string) $user->email;
        }
    }

    public function save(): void
    {
        $this->validate();
        $user = Auth::user();
        if (! $user) {
            return;
        }
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        $this->dispatch('notify', message: 'Profile updated');
    }

    public function render()
    {
        return view('livewire.customer.profile');
    }
}
