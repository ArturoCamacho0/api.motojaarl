<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
	public function index()
	{
		return Category::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string'
		]);

		if ($validate->fails()) return response()->json($validate->errors(), 400);

		$category = Category::create($request->only('name'));

		return response()->json($category, 201);
	}

	public function show($id)
	{
		return Category::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name'), [
			'name' => 'required|string'
		]);

		if ($validate->fails()) return response()->json($validate->errors(), 400);

		$category = Category::findOrFail($id);
		$category->name = $request['name'];
		$category->save();

		return response()->json($category, 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$category = Category::findOrFail($id);
		$category->delete();

		return response()->json(['message' => 'The category has been deleted'], 200);
	}

	public function search($term){
		return Category::where('name', 'LIKE', '%'.$term.'%')->get();
	}
}
