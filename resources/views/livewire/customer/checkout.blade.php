<div>
    <x-customer-nav />
    <x-notify />
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Checkout</h1>
        @if (empty($lines))
            <div class="bg-yellow-50 text-yellow-800 p-3 rounded">Your cart is empty.</div>
        @else
            <div class="bg-white rounded shadow divide-y">
                @foreach ($lines as $line)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $line['product']->image_url }}" class="w-12 h-12 object-cover rounded"
                                alt="{{ $line['product']->name }}">
                            <div>
                                <div class="font-medium">{{ $line['product']->name }}</div>
                                <div class="text-sm text-gray-600">Qty: {{ $line['qty'] }} ·
                                    ₹{{ number_format($line['price'], 2) }}</div>
                            </div>
                        </div>
                        <div class="font-semibold">₹{{ number_format($line['subtotal'], 2) }}</div>
                        <button wire:click="removeItem({{ $line['product']->id }})"
                            class="text-red-600 hover:underline text-sm">Remove</button>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex items-center justify-between">
                <div class="text-lg">Total: <span class="font-bold">₹{{ number_format($total, 2) }}</span></div>
                <button wire:click="placeOrder"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Place Order</button>
            </div>
        @endif
    </div>


</div>
