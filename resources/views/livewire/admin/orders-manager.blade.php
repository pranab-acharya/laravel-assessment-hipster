<div>
    <x-admin-nav />
    <div class="mx-auto p-6 bg-gray-50 min-h-screen grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <x-notify />
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Orders</h2>

            <div class="overflow-x-auto bg-white p-4 rounded shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($orders as $o)
                            <tr>
                                <td class="px-4 py-2">#{{ $o->id }}</td>
                                <td class="px-4 py-2">{{ $o->user->name ?? $o->user->email }}</td>
                                <td class="px-4 py-2">₹{{ number_format($o->total, 2) }}</td>
                                <td class="px-4 py-2">
                                    @if ($editingId === $o->id)
                                        <select wire:model="status" class="border rounded px-2 py-1">
                                            @foreach ($statuses as $s)
                                                <option value="{{ $s }}">{{ strtoupper($s) }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-gray-100 rounded text-sm">{{ $o->status->name }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">
                                    @if ($editingId === $o->id)
                                        <button wire:click="updateStatus"
                                            class="px-3 py-1 bg-indigo-600 text-white rounded">Save</button>
                                        <button wire:click="$set('editingId', null)"
                                            class="px-3 py-1 bg-gray-200 rounded ml-2">Cancel</button>
                                    @else
                                        <button wire:click="edit({{ $o->id }})"
                                            class="px-3 py-1 bg-blue-600 text-white rounded">Change</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
