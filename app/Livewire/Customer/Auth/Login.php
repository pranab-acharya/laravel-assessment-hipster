<?php

namespace App\Livewire\Customer\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('customer.products');
        }
    }

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            return redirect()->route('customer.products'); // redirect after login
        }

        $this->addError('email', 'Invalid credentials');
    }

    public function render()
    {
        return view('livewire.customer.auth.login');
    }
}
