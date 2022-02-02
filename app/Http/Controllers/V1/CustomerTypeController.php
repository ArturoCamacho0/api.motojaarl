<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use Illuminate\Http\Request;

class CustomerTypeController extends Controller
{
	public function index()
	{
		return CustomerType::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string'
		]);

		if ($validate->fails()) return response()->json($validate->errors(), 400);

		$customer_type = CustomerType::create($request->only('name'));
		$customer_type->save();

		return response()->json($customer_type, 201);
	}

	public function show($id)
	{
		return CustomerType::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string'
		]);

		if ($validate->fails()) return response()->json($validate->errors(), 400);

		$customer_type = CustomerType::findOrFail($id);
		$customer_type->name = $request['name'];
		$customer_type->save();

		return response()->json($customer_type, 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$customer_type = CustomerType::findOrFail($id);
		$customer_type->delete();

		return response()->json(['message' => 'The customer type has been deleted'], 200);
	}
}
