<div>
    <x-customer-nav />
    <x-notify />
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Notifications</h1>

        @if ($notifications->isEmpty())
            <div class="bg-white p-4 rounded shadow text-gray-600">No notifications yet.</div>
        @else
            <div class="bg-white rounded shadow divide-y">
                @foreach ($notifications as $n)
                    <div class="p-4 flex items-start justify-between">
                        <div>
                            <div class="font-medium">
                                {{ data_get($n->data, 'message') ?? ucfirst(str_replace('_', ' ', data_get($n->data, 'type', 'notification'))) }}
                            </div>
                            <div class="text-sm text-gray-500">#{{ $n->id }} •
                                {{ $n->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if (!$n->read_at)
                                <button wire:click="markAsRead('{{ $n->id }}')"
                                    class="text-sm px-3 py-1 bg-indigo-600 text-white rounded">Mark as read</button>
                            @else
                                <span class="text-xs px-2 py-1 bg-gray-100 rounded">Read</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $notifications->links() }}</div>
        @endif
    </div>
</div>
