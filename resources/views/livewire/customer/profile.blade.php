<div>
    <x-customer-nav />
    <x-notify />
    <div class="container mx-auto p-4 max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Profile</h1>
        <form wire:submit.prevent="save" class="bg-white p-4 rounded shadow space-y-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Name</label>
                <input type="text" wire:model.defer="name" class="w-full border rounded px-3 py-2" />
                @error('name')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" wire:model.defer="email" class="w-full border rounded px-3 py-2" />
                @error('email')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="pt-2">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Save</button>
            </div>
        </form>
    </div>

</div>
