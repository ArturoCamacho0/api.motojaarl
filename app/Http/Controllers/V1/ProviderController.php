<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
	public function index()
	{
		return Provider::all();
	}

	public function store(Request $request)
	{
		$validate = validator($request->all(), [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:providers',
			'direction_id' => 'integer',
			'phones' => 'required|array',
		]);

		if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}

		$provider = Provider::create($request->all());

		$phones = $request['phones'];

		foreach ($phones as $phone) {
			$validate = validator($phone, [
				"number" => "required|string",
				"phone_id" => "required|integer",
			]);

			if ($validate->fails()) {
				$provider->delete();
				return response()->json([
					"message" => "Validation Error",
					"error" => $validate->errors()
				], 422);
			}

			$customer_get = Provider::where("id", $provider->id)->first();
			$provider->phones()->attach($phone["phone_id"], [
				"phone_id" => $phone["phone_id"],
				"provider_id" => $customer_get->id,
				"phone_number" => $phone["number"],
			]);
		}

		return response()->json($provider, 201);
	}

	public function show($id)
	{
		return Provider::findOrFail($id);
	}

	public function update(Request $request, $id)
	{
		$validate = validator($request->all(), [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:providers,email,' . $id,
			'direction_id' => 'required|integer|exists:directions,id',
		]);

		if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}

		$provider = Provider::findOrFail($id);

		$provider->update($request->all());

		return response()->json($provider, 200);
	}

	public function destroy($id)
	{
		Provider::findOrFail($id)->delete();
		return response('Deleted Successfully', 200);
	}

	public function getPhones($id)
	{
		$provider = Provider::findOrFail($id);
		return $provider->phones;
	}

	public function getDirection($id)
	{
		$provider = Provider::findOrFail($id);
		return $provider->direction;
	}

	public function deletePhone($id, $phone_id)
	{
		$provider = Provider::findOrFail($id);
		$provider->phones()->detach($phone_id);
		return response('Deleted Successfully', 200);
	}
}
