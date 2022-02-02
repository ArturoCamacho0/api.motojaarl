<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
	public function index()
	{
		return Sale::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'total' => 'required|number',
			'discount' => 'number',
			'user_id' => 'number|required'
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$sale = Sale::create($request->only([
			'total',
			'discount',
			'user_id'
		]));

		return response()->json($sale, 201);
	}

	public function show($id)
	{
		return Sale::findOrFail($id);
	}

	public function update(Request $request, $id)
	{
		$validate = validator($request->only([
			'total' => 'required|number',
			'discount' => 'number',
			'user_id' => 'number|required'
		]));

		if ($validate->fails())
			return response()->json($validate->errors(), 400);

		$sale = Sale::findOrFail($id);
		$sale->total = $request['total'];
		$sale->discount = $request['discount'];
		$sale->user_id = $request['user_id'];
		$sale->save();

		return response($sale, 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$sale = Sale::findOrFail($id);
		$sale->delete();

		return response()->json(['message' => 'The sale has been deleted'], 200);
	}

	public function getByUserId($id)
	{
		return Sale::where('user_id', $id)->get();
	}

	public function getByDate(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'start' => 'required',
			'end' => 'required',
			'order' => 'required'
		]));

		if ($validate->fails() || $request['start'] > $request['end'] || $request['start'] == null
			|| $request['end'] == null || $request['order'] == null)
			return response()->json([
				"message" => "An error has occurred"
			], 400);

		$start = Carbon::parse($request['start']);
		$end = Carbon::parse($request['end']);

		$sale = Sale::whereBetween('created_at', [$start, $end])->orderBy('created_at', $request['order'])->get();

		return response()->json($sale, 200);
	}

	public function getTotalSales(): \Illuminate\Http\JsonResponse
	{
		return response()->json(Sale::sum('total'), 200);
	}

	public function getTotalByDate(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'start' => 'required',
			'end' => 'required',
		]));

		if ($validate->fails() || $request['start'] > $request['end'] || $request['start'] == null
			|| $request['end'] == null)
			return response()->json([
				"message" => "An error has occurred"
			], 400);

		$start = Carbon::parse($request['start']);
		$end = Carbon::parse($request['end']);

		$sale = Sale::whereBetween('created_at', [$start, $end])->sum('total');

		return response()->json($sale, 200);
	}
}
