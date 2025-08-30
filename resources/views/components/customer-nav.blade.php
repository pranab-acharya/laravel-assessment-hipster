@php
    $currentRoute = request()->route()->getName();
@endphp

<nav class="w-full bg-white shadow mb-4">
    <div class="container mx-auto px-4 py-3 flex items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.products') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'customer.products' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                Products
            </a>
            <a href="{{ route('customer.checkout') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'customer.checkout' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                Checkout
            </a>
            <a href="{{ route('customer.orders') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'customer.orders' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                My Orders
            </a>
            <a href="{{ route('customer.profile') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'customer.profile' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                My Profile
            </a>
            <a href="{{ route('customer.settings') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'customer.settings' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                My Settings
            </a>
            <a href="{{ route('customer.notifications') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'customer.notifications' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                My Notifications
            </a>
        </div>

        <div class="ml-auto">
            <a href="{{ route('customer.logout') }}" class="text-sm text-red-700 hover:text-red-600 font-bold"
                wire:navigate>
                Logout
            </a>
        </div>
    </div>
</nav>
