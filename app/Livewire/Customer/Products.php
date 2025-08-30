<?php

namespace App\Livewire\Customer;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products')]
class Products extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    public array $cart = [];

    // Initialize cart from session
    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    // Reset pagination when the search term changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Add product to cart
    public function addToCart($productId)
    {
        $cart = session()->get('cart', []);
        $product = Product::find($productId);
        if (! $product) {
            $this->dispatch('notify', message: 'Product not found.');

            return;
        }

        $currentQty = $cart[$productId]['quantity'] ?? 0;
        if ($product->stock <= $currentQty) {
            $this->dispatch('notify', message: 'Not enough stock available.');

            return;
        }

        $cart[$productId] = [
            'name' => $product->name,
            'price' => (float) $product->price,
            'quantity' => $currentQty + 1,
        ];

        session()->put('cart', $cart);
        $this->cart = $cart;

        $this->dispatch('notify', message: 'Product added to cart!');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search !== '', function ($query) {
                $term = "%{$this->search}%";
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('category', 'like', $term)
                        ->orWhere('description', 'like', $term);
                });
            })
            ->latest()
            ->paginate(12);

        return view('livewire.customer.products', compact('products'));
    }
}
