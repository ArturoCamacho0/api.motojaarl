<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
	public function index()
	{
		return Business::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string',
			'directions' => 'array',
		]);

		if ($validate->fails()) return response()->json($validate->errors(), 400);

		$business = Business::create($request->only('name'));

		if ($request->has('directions')) {
			foreach ($request['directions'] as $direction) {
				$validate = validator($request->only('directions'), [
					'direction_id' => 'required|integer|exists:directions,id',
				]);

				$business_get = Business::where('id', $business->id)->first();

				$business_get->directions()->attach($direction['id'], [
					'direction_id' => $direction['id'],
					'business_id' => $business->id
				]);
			}
		}

		return response()->json($business, 201);
	}

	public function show($id)
	{
		return Business::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string'
		]);

		if ($validate->fails()) return response()->json($validate->errors(), 400);

		$business = Business::findOrFail($id);
		$business->name = $request['name'];
		$business->save();

		return response()->json($business, 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$business = Business::findOrFail($id);
		$business->delete();

		return response()->json(['message' => 'The business has been deleted'], 200);
	}

	public function getDirections($id)
	{
		return Business::findOrFail($id)->directions;
	}

	public function deleteDirection($id, $direction_id): \Illuminate\Http\JsonResponse
	{
		$business = Business::findOrFail($id);
		$business->directions()->detach($direction_id);
		return response()->json(['message' => 'The direction has been deleted'], 200);
	}
}
