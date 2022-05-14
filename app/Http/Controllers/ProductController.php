<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ProductResource;
use Illuminate\Contracts\Validation\Validator;

class ProductController extends Controller
{
    /**
     * Retrieve a listing of the resource.
     *
     * @return \App\Http\Resources\ProductResource
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->has('stock'),
                fn ($q) => $q->withStockSum())
            ->when($request->has('order'),
                fn ($q) => $q->withStockSum()->orderByStock($request->input('order')))
            ->when($request->has('available'),
                fn ($q) => $q->withStockSum()->hasAvailableStock())
        ->paginate();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ProductResource|Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'bail|required|max:255|filled|alpha_num|unique:products,code',
                'name' => 'required|max:255|filled',
                'description' => 'required|max:255|filled'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response($e->getMessage(), 422);
        }

        return new ProductResource(
            Product::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'],
            ])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function show(Request $request, $code)
    {
        $product = Product::where('code', $code)
        ->when($request->has('stock'),
            fn ($q) => $q->withStockSum())
        ->firstOrFail();

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $product_code
     * @return \App\Http\Resources\ProductResource|Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        try {
            $validated = $request->validate([
                'name' => 'max:255|filled',
                'description' => 'max:255|filled'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response($e->getMessage(), 422);
        }

        $product = Product::where('code', $code)->firstOrFail();

        if($request->has('name')) {
            $product->name = $request->input('name');
        }

        if($request->has('description')) {
            $product->description = $request->input('description');
        }

        $product->save();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $product_code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        $product->delete();
        return response('', 204);
    }
}
