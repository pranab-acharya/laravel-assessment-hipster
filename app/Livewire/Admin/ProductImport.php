<?php

namespace App\Livewire\Admin;

use App\Jobs\ImportProductsChunk;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Product Import')]
class ProductImport extends Component
{
    use WithFileUploads;

    #[Rule('required|file|mimes:csv,txt|max:20480')]
    public $file;

    public ?string $batchId = null;
    public array $stats = [
        'rows' => 0,
        'inserted' => 0,
        'skipped' => 0,
        'errors' => 0,
    ];

    public function updatedFile()
    {
        $this->reset('batchId');
    }

    public function import()
    {
        $this->validate();

        $path = $this->file->store('imports');
        $fullPath = Storage::path($path);

        $chunkSize = 1000;
        $jobs = [];
        $header = null;
        $buffer = [];

        $handle = fopen($fullPath, 'rb');
        if ($handle === false) {
            $this->addError('file', 'Unable to read uploaded file.');
            $this->dispatch('notify', message: 'Unable to read uploaded file');

            return;
        }

        $rowNum = 0;
        while (($row = fgetcsv($handle)) !== false) {
            // Expect header in first row
            if ($rowNum === 0) {
                // Strip UTF-8 BOM on first cell if present and normalize header names
                if (! empty($row)) {
                    $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $row[0]);
                }
                $header = array_map(function ($h) {
                    $h = is_string($h) ? $h : (string) $h;

                    return strtolower(trim($h));
                }, $row);
                $rowNum++;

                continue;
            }
            $buffer[] = $row;
            $rowNum++;
            if (count($buffer) >= $chunkSize) {
                $jobs[] = new ImportProductsChunk($header, $buffer);
                $buffer = [];
            }
        }
        fclose($handle);
        if (! empty($buffer)) {
            $jobs[] = new ImportProductsChunk($header, $buffer);
        }

        if (empty($jobs)) {
            $this->addError('file', 'No data rows detected in CSV.');
            $this->dispatch('notify', message: 'No data rows detected in CSV');

            return;
        }

        $batch = Bus::batch($jobs)->name('Products Import')->dispatch();
        $this->batchId = $batch->id;
        $this->dispatch('notify', message: 'Import started');
        session()->flash('message', 'Import started in background. You can stay on this page to see progress.');
    }

    public function getBatchProperty(): ?Batch
    {
        return $this->batchId ? Bus::findBatch($this->batchId) : null;
    }

    public function render()
    {
        return view('livewire.admin.product-import');
    }
}
