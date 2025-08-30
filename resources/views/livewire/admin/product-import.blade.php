<div>
    <x-admin-nav />
    <div class=" mx-auto p-6">
        <x-notify />
        <h2 class="text-2xl font-semibold mb-4">Bulk Product Import</h2>

        @if (session()->has('message'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="import" class="space-y-4 bg-white p-4 rounded shadow">
            <div>
                <label class="block text-sm font-medium mb-1">CSV File</label>
                <input type="file" wire:model="file" accept=".csv,text/csv" class="border p-2 rounded w-full">
                @error('file')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
                    @disabled(!$file) wire:loading.attr="disabled" wire:target="import,file">
                    Start Import
                </button>
                <a href="/products_sample_import.csv" class="px-3 py-2 bg-gray-200 rounded" download>Download sample
                    CSV</a>
            </div>
        </form>

        @if ($this->batch)
            <div wire:poll.2s class="mt-6 bg-white p-4 rounded shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium">Import Progress</div>
                        <div class="text-sm text-gray-600">Batch ID: {{ $this->batch->id }}</div>
                    </div>
                    <div class="text-sm">
                        {{ $this->batch->processedJobs() }} / {{ $this->batch->totalJobs }} jobs
                    </div>
                </div>
                <div class="mt-3 w-full bg-gray-200 rounded h-2">
                    @php
                        $total = max(1, $this->batch->totalJobs);
                        $pct = floor(($this->batch->processedJobs() / $this->batch->totalJobs) * 100);
                    @endphp
                    <div class="bg-blue-600 h-2 rounded" style="width: {{ $pct }}%"></div>
                </div>
                @if ($this->batch->failedJobs > 0)
                    <div class="mt-2 text-red-600 text-sm">Failed Jobs: {{ $this->batch->failedJobs }}</div>
                @endif
            </div>
        @endif
    </div>

</div>
