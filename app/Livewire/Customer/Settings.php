<?php

namespace App\Livewire\Customer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Settings')]
class Settings extends Component
{
    #[Rule('nullable|min:8')]
    public string $password = '';

    #[Rule('same:password')]
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        $this->validate();
        if ($this->password === '') {
            $this->addError('password', 'Please enter a new password.');

            return;
        }
        $user = Auth::user();
        if (! $user) {
            return;
        }
        $user->forceFill(['password' => Hash::make($this->password)])->save();
        $this->reset(['password', 'password_confirmation']);
        $this->dispatch('notify', message: 'Password updated');
    }

    public function render()
    {
        return view('livewire.customer.settings');
    }
}
