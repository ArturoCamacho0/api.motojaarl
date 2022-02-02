<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
	public function index()
	{
		return Phone::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'name' => 'required'
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$phone = Phone::create($request->only('name'));

		return response()->json(['phone' => $phone], 201);
	}

	public function show($id)
	{
		return Phone::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'name' => 'required'
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$phone = Phone::findOrFail($id);
		$phone->name = $request['name'];
		$phone->save();

		return response()->json(['phone' => $phone], 201);
	}

	public function destroy($id)
	{
		$phone = Phone::findOrFail($id);
		$phone->delete();

		return response()->json(['message' => 'The phone has been deleted'], 200);
	}
}
