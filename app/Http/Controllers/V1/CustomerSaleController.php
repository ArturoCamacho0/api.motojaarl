<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomerSale;
use Illuminate\Http\Request;

class CustomerSaleController extends Controller
{
	public function index()
	{
		return CustomerSale::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'discount' => 'required|numeric',
			'user_id' => 'required|integer',
			'customer_id' => 'required|integer',
			'products' => 'required|array',
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$sale = CustomerSale::create($request->only([
			'discount',
			'user_id',
			'customer_id',
		]));

		$total = 0;

		foreach ($request['products'] as $product) {
			$validate = validator($product, [
				'key' => 'required|string',
				'returned' => 'boolean',
				'quantity' => 'required|integer',
			]);

			$sale_get = CustomerSale::findOrFail($sale->id);

			$product_get = $sale_get->products()->where('key', $product['key'])->first();

			if(!$product['returned']){
				$total = $total + $product_get->price * $product['quantity'];
			}

			$sale_get->products()->attach($product['key'],
				[
					'customer_sale_id' => $sale->id,
					'product_key' => $product['key'],
					'quantity' => $product['quantity'],
					'total' => $product_get->price * $product['quantity'],
					'returned' => $product['returned'] ? 1 : 0,
				]
			);
		}

		$sale->total = $total;
		$sale->save();

		return response()->json([
			"message" => "Sale created successfully",
		], 201);
	}

	public function show($id)
	{
		return CustomerSale::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'total' => 'required|numeric',
			'discount' => 'required|numeric',
			'user_id' => 'required|integer',
			'customer_id' => 'required|integer',
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$sale = CustomerSale::findOrFail($id);

		$sale->update($request->only([
			'total',
			'discount',
			'user_id',
			'customer_id',
		]));

		return response()->json([
			"message" => "Sale updated successfully",
		], 200);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$sale = CustomerSale::findOrFail($id);

		$sale->delete();

		return response()->json([
			"message" => "Sale deleted successfully",
		], 200);
	}

	public function getByCustomer($id)
	{
		return CustomerSale::where('customer_id', $id)->get();
	}

	public function getByUser($id)
	{
		return CustomerSale::where('user_id', $id)->get();
	}

	public function getProducts($id)
	{
		return CustomerSale::findOrFail($id)->products;
	}

	public function getProductsByCustomer($id)
	{
		return CustomerSale::where('customer_id', $id)->get()->products;
	}

	public function getProductsByUser($id)
	{
		return CustomerSale::where('user_id', $id)->get()->products;
	}

	public function deleteProduct($id, $product_id): \Illuminate\Http\JsonResponse
	{
		$sale = CustomerSale::findOrFail($id);

		$sale->products()->detach($product_id);

		return response()->json([
			"message" => "Product deleted successfully",
		], 200);
	}

	public function updateProductSold(Request $request, $id, $product_id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'quantity' => 'required|integer',
			'returned' => 'boolean',
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$sale = CustomerSale::findOrFail($id);
		$product = $sale->products()->findOrFail($product_id);

		$sale->products()->updateExistingPivot($product_id, [
			'quantity' => $request['quantity'],
			'returned' => $request['returned'] ? 1 : 0,
			'total' => $request['quantity'] * $product->price,
		]);

		return response()->json([
			"message" => "Product updated successfully",
		], 200);
	}

	public function getTotal(){
		$customer_sales = CustomerSale::all();
		$total = 0;

		$sales = $customer_sales->products;

		foreach ($sales as $sale) {
			$total = $total + $sale->total;
		}

		return $total;
	}
}
