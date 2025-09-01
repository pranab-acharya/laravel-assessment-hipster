@props(['users' => [], 'type' => 'customer'])

<div class="space-y-2">
    @forelse ($users as $user)
        <div class="flex items-center justify-between p-3 bg-{{ $type === 'admin' ? 'blue' : 'green' }}-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div
                    class="w-2 h-2 {{ $type === 'admin' ? 'bg-blue-500' : 'bg-green-600' }} rounded-full animate-pulse mr-2">
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $user['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
                </div>
            </div>
            <div class="text-xs text-gray-500">
                @if ($type === 'admin' && $user['id'] === auth('admin')->id())
                    <span class="text-blue-600 font-medium">You</span>
                @else
                    Active now
                @endif
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500 italic">No {{ $type }}s online</p>
    @endforelse
</div>
