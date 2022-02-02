<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
	public function index()
	{
		return Price::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string'
		]);

		if ($validate->fails()) return response()->json($validate->fails(), 400);

		$price = Price::create($request->only('name'));

		return response()->json($price, 201);
	}

	public function show($id)
	{
		return Price::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string'
		]);

		if ($validate->fails()) return response()->json($validate->fails(), 400);

		$price = Price::findOrFail($id);
		$price->name = $request['name'];
		$price->save();

		return response()->json($price, 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$price = Price::findOrFail($id);
		$price->delete();

		return response()->json(['message' => 'The price has been deleted'], 200);
	}
}
