<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    use PhotoUploadable;

    public function index() {
        return inertia('Admin/Product', [
            'products' => Product::search(request('search'))->paginate(5),
            'filters'  => request()->only(['search']),
        ]);
    }

    public function show(Product $product) {
        //
    }

    public function store(Request $request) {
        $request->validate([
            'id'                  => 'int|nullable',
            'name'                => 'required|unique:products|string',
            'content'             => 'required|string|min:5',
            'price'               => 'required|int|min:10',
            'stripe_price_id'     => 'required|string|starts_with:price_',
            'photo_external_path' => 'active_url|nullable',
        ]);

        $product = Product::firstOrNew(['id' => $request['id']]);
        $oldPath = $product->poster_url;
        $updatedPath = $this->upload($oldPath);

        Product::updateOrCreate(['id' => $request['id']], [
            'name'            => $request['name'],
            'content'         => $request['content'],
            'price'           => $request['price'],
            'stripe_price_id' => $request['stripe_price_id'],
            'poster_url'      => $updatedPath ?? $request['photo_external_path'] ?? $oldPath,
        ]);
    }

    public function deletePoster(Request $request): void {
        $this->handleDeleteFile();
        Product::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(Product $product) {
        $this->deleteFileIfExist($product->poster_url);
        $product->delete();
    }
}
