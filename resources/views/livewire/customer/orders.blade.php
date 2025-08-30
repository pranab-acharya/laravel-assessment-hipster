<div>
    <x-customer-nav />
    <x-notify />
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">My Orders</h1>
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-4 py-2">Order #</th>
                        <th class="text-left px-4 py-2">Status</th>
                        <th class="text-left px-4 py-2">Total</th>
                        <th class="text-left px-4 py-2">Items</th>
                        <th class="text-left px-4 py-2">Placed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-t">
                            <td class="px-4 py-2">#{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->status->name }}</td>
                            <td class="px-4 py-2">₹{{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-2">{{ $order->orderItems->sum('quantity') }}</td>
                            <td class="px-4 py-2">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    </div>

</div>
