<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
	public function index()
	{
		return Sale::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'products' => 'required|array',
			'quantity' => 'required|integer',
			'total' => 'required|numeric',
			'user_id' => 'required|integer',
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$sale = Sale::create($request->only([
			'quantity',
			'total',
			'user_id',
		]));

		foreach ($request['products'] as $product) {
			$validate = validator($product, [
				'key' => 'required|string',
				'returned' => 'boolean',
				'quantity' => 'required|integer',
			]);

			$sale_get = Sale::findOrFail($sale->id);

			$sale_get->products()->attach($product['key'],
				[
					'sale_id' => $sale->id,
					'product_key' => $product['key'],
					'quantity' => $product['quantity'],
					'total' => $product['price'] * $product['quantity'],
					'returned' => $product['returned'],
				]
			);
		}


		return response()->json([
			"message" => "Sale created successfully",
		], 201);
	}

	public function show($id)
	{
		return Sale::findOrFail($id);
	}

	public function update(Request $request, $id)
	{
		$validate = validator($request->only([
			'total' => 'required|number',
			'discount' => 'number',
			'user_id' => 'number|required'
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$sale = Sale::findOrFail($id);
		$sale->total = $request['total'];
		$sale->discount = $request['discount'];
		$sale->user_id = $request['user_id'];
		$sale->save();

		return response($sale, 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$sale = Sale::findOrFail($id);
		$sale->delete();

		return response()->json(['message' => 'The sale has been deleted'], 200);
	}

	public function getByUserId($id)
	{
		return Sale::where('user_id', $id)->get();
	}

	public function getByDate(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'start' => 'required',
			'end' => 'required',
			'order' => 'required'
		]));

		if ($validate->fails() || $request['start'] > $request['end'] || $request['start'] == null
			|| $request['end'] == null || $request['order'] == null)
			return response()->json([
				"message" => "An error has occurred"
			], 400);

		$start = Carbon::parse($request['start']);
		$end = Carbon::parse($request['end']);

		$sale = Sale::whereBetween('created_at', [$start, $end])->orderBy('created_at', $request['order'])->get();

		return response()->json($sale, 200);
	}

	public function getTotalSales(): \Illuminate\Http\JsonResponse
	{
		return response()->json(Sale::sum('total'), 200);
	}

	public function getTotalByDate(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'start' => 'required',
			'end' => 'required',
		]));

		if ($validate->fails() || $request['start'] > $request['end'] || $request['start'] == null
			|| $request['end'] == null)
			return response()->json([
				"message" => "An error has occurred"
			], 400);

		$start = Carbon::parse($request['start']);
		$end = Carbon::parse($request['end']);

		$sale = Sale::whereBetween('created_at', [$start, $end])->sum('total');

		return response()->json($sale, 200);
	}

	public function getProductsBestSeller(){
		$products = Product::all();
		$products_sold = [];
		foreach($products as $product){
			$products_sold[$product->id] = $product->sales()->sum('quantity');
		}
		arsort($products_sold);
		$products_sold = array_slice($products_sold, 0, 5);
		$products_sold = array_keys($products_sold);
		return Product::whereIn('id', $products_sold)->get();
	}
}
