<div>
    <x-customer-nav />
    <x-notify />
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-center">Products</h1>

        <div class="mb-4">
            <input type="text" wire:model.live="search" placeholder="Search products..."
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white p-4 rounded shadow flex flex-col justify-between">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                        class="w-full h-40 object-cover rounded mb-2" style="object-fit: contain;">
                    <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                    <p class="text-gray-700 mt-1">₹{{ $product->price }}</p>
                    @if ($product->stock > 0)
                        <button wire:click="addToCart({{ $product->id }})"
                            class="mt-2 bg-indigo-600 text-white py-1 rounded hover:bg-indigo-700 transition duration-200">
                            Add to Cart
                        </button>
                    @else
                        <span class="text-red-600 mt-2">Out of Stock</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
