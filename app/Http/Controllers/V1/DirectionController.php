<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
	public function index()
	{
		return Direction::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('street', 'number', 'state', 'cp'), [
			'street' => 'required|string',
			'number' => 'required|string',
			'state' => 'required|string',
			'cp' => 'required|string'
		]);

		if ($validate->fails())
			return response()->json(['error' => $validate->errors()], 400);

		$direction = Direction::create($request->only('street', 'number', 'state', 'cp'));

		return response()->json(['direction' => $direction], 201);
	}

	public function show($id)
	{
		return Direction::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('street', 'number', 'state', 'cp'), [
			'street' => 'required',
			'number' => 'required',
			'state' => 'required',
			'cp' => 'required'
		]);

		if ($validate->fails())
			return response()->json(['error' => $validate->errors()], 400);

		$direction = Direction::findOrFail($id);
		$direction->street = $request['street'];
		$direction->number = $request['number'];
		$direction->state = $request['state'];
		$direction->cp = $request['cp'];
		$direction->save();

		return response()->json(['direction' => $direction], 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$direction = Direction::findOrFail($id);
		$direction->delete();

		return response()->json(['message' => 'The direction has been deleted'], 200);
	}
}
