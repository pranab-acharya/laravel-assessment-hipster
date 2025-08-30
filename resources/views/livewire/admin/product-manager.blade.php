<div>
    <x-admin-nav />
    <div class="mx-auto p-4 sm:p-6 bg-gray-50 min-h-screen">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <x-notify />

                <h2 class="text-2xl font-bold mb-6 text-gray-800">Product Management</h2>

                <div class="bg-white p-6 rounded shadow mb-6">
                    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4"
                        enctype="multipart/form-data">

                        <div>
                            <label class="block text-gray-700 mb-1">Product Name</label>
                            <input wire:model="name" type="text" placeholder="Product Name"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            @error('name')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1">Category</label>
                            <input wire:model="category" type="text" placeholder="Category"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('category')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" placeholder="Description"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('description')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1">Price</label>
                            <input wire:model="price" type="number" step="0.01" placeholder="Price"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('price')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1">Stock</label>
                            <input wire:model="stock" type="number" placeholder="Stock"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('stock')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-1">Image</label>
                            <input wire:model="imageFile" type="file" accept="image/*"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('imageFile')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                @if ($imageFile)
                                    <div class="text-sm text-gray-600 mb-1">Preview (not saved yet):</div>
                                    <img src="{{ $imageFile->temporaryUrl() }}" alt="Selected image preview"
                                        class="h-32 w-32 object-cover rounded border">
                                @elseif ($existingImage)
                                    <div class="text-sm text-gray-600 mb-1">Current image:</div>
                                    <img src="{{ asset('storage/images/' . $existingImage) }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/' . $existingImage) }}';"
                                        alt="Existing product image" class="h-32 w-32 object-cover rounded border">
                                @else
                                    <div class="text-sm text-gray-600 mb-1">No image selected. A default will be used.
                                    </div>
                                    <img src="{{ asset('images/product_default.webp') }}" alt="Default product image"
                                        class="h-32 w-32 object-cover rounded border opacity-80">
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-2">
                            <button type="submit"
                                class="bg-blue-500 cursor-pointer text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                {{ $editingId ? 'Update' : 'Create' }} Product
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mb-4">
                    <input wire:model.live="search" type="text" placeholder="Search products..."
                        class="w-full md:w-1/2 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="overflow-x-auto bg-white p-4 rounded shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 text-gray-700"><img src="{{ $product->image_url }}"
                                            alt="{{ $product->name }}" class="w-16 h-16 object-cover"></td>
                                    <td class="px-6 py-4 text-gray-700">{{ $product->name }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $product->category }}</td>
                                    <td class="px-6 py-4 text-gray-700">₹{{ $product->price }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $product->stock }}</td>
                                    <td class="px-6 py-4 space-x-2">
                                        <button wire:click="edit({{ $product->id }})"
                                            class="bg-yellow-400 cursor-pointer text-white px-3 py-1 rounded hover:bg-yellow-500 transition">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $product->id }})"
                                            class="bg-red-500 cursor-pointer text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
