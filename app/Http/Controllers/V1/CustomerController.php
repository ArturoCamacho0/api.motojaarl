<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
	public function index()
	{
		return Customer::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->all(), [
			"name" => "required|string",
			"email" => "required|email",
			"business_id" => "required|integer",
			"customer_type_id" => "required|integer",
			"phones" => "required|array",
		]);

		if ($validate->fails()) {
			return response()->json([
				"message" => "Validation Error",
				"error" => $validate->errors()
			], 422);
		}

		$customer = Customer::create($request->all());

		$phones = $request["phones"];

		foreach ($phones as $phone) {
			$validate = validator($phone, [
				"number" => "required|string",
				"phone_id" => "required|integer",
			]);

			if ($validate->fails()) {
				$customer->delete();
				return response()->json([
					"message" => "Validation Error",
					"error" => $validate->errors()
				], 422);
			}

			$customer_get = Customer::where("id", $customer->id)->first();
			$customer->phones()->attach($phone["phone_id"], [
				"phone_id" => $phone["phone_id"],
				"customer_id" => $customer_get->id,
				"phone_number" => $phone["number"],
			]);
		}

		return response()->json($customer, 201);
	}

	public function show($id)
	{
		return Customer::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->all(), [
			"name" => "required|string",
			"email" => "required|email",
			"business_id" => "required|integer",
			"customer_type_id" => "required|integer",
		]);

		if ($validate->fails()) {
			return response()->json([
				"message" => "Validation Error",
				"error" => $validate->errors()
			], 422);
		}

		$customer = Customer::findOrFail($id);
		$customer->update($request->all());

		return response()->json([
			"message" => "Success",
			"data" => $customer
		], 200);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$customer = Customer::findOrFail($id);
		$customer->delete();

		return response()->json([
			"message" => "Successfully deleted"
		], 200);
	}

	public function getByBusiness($business_id)
	{
		return Customer::where('business_id', $business_id)->get();
	}

	public function getByType($type)
	{
		return Customer::where('customer_type_id', $type)->get();
	}

	public function getPhones($id)
	{
		return Customer::findOrFail($id)->phones;
	}

	public function deletePhone($id, $phone_id): \Illuminate\Http\JsonResponse
	{
		$customer = Customer::findOrFail($id);
		$customer->phones()->detach($phone_id);

		return response()->json([
			"message" => "Successfully deleted"
		], 200);
	}
}
