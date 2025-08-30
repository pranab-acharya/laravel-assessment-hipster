<div>
    <x-customer-nav />
    <x-notify />
    <div class="container mx-auto p-4 max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Settings</h1>
        <form wire:submit.prevent="updatePassword" class="bg-white p-4 rounded shadow space-y-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">New Password</label>
                <input type="password" wire:model.defer="password" class="w-full border rounded px-3 py-2" />
                @error('password')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Confirm Password</label>
                <input type="password" wire:model.defer="password_confirmation"
                    class="w-full border rounded px-3 py-2" />
                @error('password_confirmation')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="pt-2">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Update Password</button>
            </div>
        </form>
    </div>

</div>
