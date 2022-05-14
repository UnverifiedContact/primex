<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stock;
use App\Models\Product;
use App\Http\Resources\StockResource;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;

class StockController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $product_code
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $product_code)
    {
        try {
            $validated = $request->validate([
                'on_hand' => 'required|max:255|filled',
                'taken' => 'required|max:255|filled',
                'production_date' => 'date_format:d/m/Y'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e){
            return response($e->getMessage(), 422);
        }

        $product = Product::where('code', $product_code)->firstOrFail();
        $stock = Stock::create([
            'product_code' => $product_code,
            'on_hand' => $request->on_hand,
            'taken' => $request->taken,
            'production_date' => Carbon::createFromFormat('d/m/Y', $request->production_date)->toDateString()
        ]);

        return new StockResource($stock);
    }

    /**
     * Retrieve a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product_index(Request $request, $product_code)
    {
        $product = Product::where('code', $product_code)->firstOrFail();
        $stock = Stock::where('product_code', $product_code)
            ->orderBy('production_date', 'ASC')->paginate();
        return StockResource::collection($stock);
    }
}

