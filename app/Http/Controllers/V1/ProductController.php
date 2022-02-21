<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function index()
	{
		return Product::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->all(), [
			'key' => 'required|unique:products',
			'name' => 'required|string|max:255',
			'description' => 'required|string',
			'stock' => 'required|integer',
			'minimum' => 'required|integer',
			'category_id' => 'required|integer',
			'prices' => 'required|array',
		]);

		if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}

		$product = Product::create($request->only(
			'key',
			'name',
			'description',
			'stock',
			'minimum',
			'category_id'
		));

		$prices = $request['prices'];

		foreach ($prices as $price) {
			$validate = validator($price, [
				'id' => 'required|integer',
				'price' => 'required|numeric',
			]);

			if ($validate->fails()) {
				$product->delete();
				return response()->json($validate->errors(), 400);
			}

			$product = Product::where('key', $product->key)->first();
			$product->prices()->attach(
				$price['id'],
				[
					'price_id' => $price['id'],
					'price' => $price['price'],
					'product_key' => $product->key
				]
			);
		}

		return response()->json([
			"message" => "Product created successfully",
		], 201);
	}

	public function show($id)
	{
		return Product::findOrFail($id);
	}

	public function update(Request $request, $id)
	{
		$product = Product::findOrFail($id);

		$validate = validator($request->all(), [
			'key' => 'required|unique:products,key,' . $product->id,
			'name' => 'required|string|max:255',
			'description' => 'required|string',
			'stock' => 'required|integer',
			'minimum' => 'required|integer',
			'category_id' => 'required|integer',
		]);

		if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}

		$product->update($request->only(
			'key',
			'name',
			'description',
			'stock',
			'minimum',
			'category_id'
		));

		return response()->json($product, 200);
	}

	public function destroy($id)
	{
		$product = Product::where('key', $id);

		$product->delete();

		return response()->json(["message" => "The product has been deleted"], 204);
	}

	public function getByCategory($id)
	{
		return Product::where('category_id', $id)->get();
	}

	public function search($term)
	{
		return Product::where('name', 'like', '%' . $term . '%')->get();
	}
}
