<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;

    public function mount()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
    }

    public function login()
    {
        $credentials = ['email' => $this->email, 'password' => $this->password];

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        $this->addError('email', 'Invalid credentials');
    }

    public function render()
    {
        return view('livewire.admin.auth.login');
    }
}
