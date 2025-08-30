<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

#[Title('Product Manager')]
class ProductManager extends Component
{
    use WithFileUploads, WithPagination;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('nullable|string|max:1000')]
    public ?string $description = null;

    #[Rule('required|numeric|min:0')]
    public float $price = 0.0;

    #[Rule('required|string|max:255')]
    public string $category = '';

    #[Rule('required|integer|min:0')]
    public int $stock = 0;

    public ?int $editingId = null;
    public ?string $existingImage = null;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Rule('nullable|image|max:2048')]
    public $imageFile;

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'stock' => $this->stock,
        ];

        if ($this->imageFile) {
            $filename = uniqid('prod_') . '.' . $this->imageFile->getClientOriginalExtension();
            $this->imageFile->storeAs('images', $filename, 'public');
            try {
                $this->imageFile->storeAs('', 'images/' . $filename, 'local');
            } catch (Throwable $e) {
                // ignore fallback errors
            }
            $data['image'] = $filename;
        } elseif ($this->editingId) {
            if ($this->existingImage) {
                $data['image'] = $this->existingImage;
            }
        }

        if ($this->editingId) {
            Product::find($this->editingId)->update($data);
            session()->flash('message', 'Product updated successfully!');
        } else {
            // Default image when creating without upload
            $createData = $data;
            if (! isset($createData['image'])) {
                $createData['image'] = 'default-product.jpg';
            }
            Product::create($createData);
            session()->flash('message', 'Product created successfully!');
        }

        $this->reset(['name', 'description', 'price', 'category', 'stock', 'editingId', 'imageFile', 'existingImage']);
        $this->resetPage();
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $this->editingId = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category = $product->category;
        $this->stock = $product->stock;
        $this->existingImage = $product->image;
        $this->imageFile = null;
    }

    public function delete($id)
    {
        Product::find($id)->delete();
        session()->flash('message', 'Product deleted successfully!');
        $this->resetPage();
    }

    public function cancel()
    {
        $this->reset(['name', 'description', 'price', 'category', 'stock', 'editingId']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->when(
                $this->search,
                fn ($query) => $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(10);

        return view('livewire.admin.product-manager', compact('products'));
    }
}
