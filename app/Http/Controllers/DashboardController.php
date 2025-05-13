<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function products(Request $request)
    {
        $q = $request->get('q');

        $products = Product::when($q, function($query) use ($q) {
            return $query->where('name', 'like', "%$q%")
                         ->orWhere('slug', 'like', "%$q%");
        })->paginate(10);

        return view('dashboard.products.index', compact('products', 'q'));
    }

    public function createProduct()
    {
        $categories = Categories::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'product_category_id' => 'required|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $product = new Product();
        $product->fill($validated);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
            $product->image_url = $imagePath;
        }

        $product->save();

        return redirect()->route('dashboard.products.index')->with('successMessage', 'Product created successfully!');
    }

    public function editProduct(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Categories::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $product->id . '|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'product_category_id' => 'required|exists:product_categories,id',
        ]);

        $product->update($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Product deleted successfully!');
    }
}
