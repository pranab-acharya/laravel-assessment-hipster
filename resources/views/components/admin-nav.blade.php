@php
    $currentRoute = request()->route()->getName();
@endphp

<nav class="w-full bg-white shadow mb-4">
    <div class="mx-auto px-4 py-3 flex items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'admin.dashboard' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                Dashboard
            </a>
            <a href="{{ route('admin.products') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'admin.products' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                Products
            </a>
            <a href="{{ route('admin.products.import') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'admin.products.import' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                Import
            </a>
            <a href="{{ route('admin.orders') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'admin.orders' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                Orders
            </a>
            <a href="{{ route('admin.notifications') }}"
                class="text-sm hover:text-indigo-600 {{ $currentRoute === 'admin.notifications' ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}"
                wire:navigate>
                Notifications
            </a>
        </div>

        <div class="ml-auto">
            <a href="{{ route('admin.logout') }}" class="text-sm text-red-700 hover:text-red-600 font-bold"
                wire:navigate>
                Logout
            </a>
        </div>
    </div>
</nav>
