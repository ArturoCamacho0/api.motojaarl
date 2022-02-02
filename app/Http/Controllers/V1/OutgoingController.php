<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Outgoing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OutgoingController extends Controller
{
	public function index()
	{
		return Outgoing::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'concept' => 'required',
			'amount' => 'required',
			'user_id' => 'required',
		]));;

		if ($validate->fails()) {
			return response()->json($validate->errors(), 422);
		}

		$outgoing = Outgoing::create($request->only([
			'concept',
			'amount',
			'user_id',
		]));

		return response()->json($outgoing, 201);
	}

	public function show($id)
	{
		return Outgoing::findOrFail($id);
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$outgoing = Outgoing::findOrFail($id);

		$validate = validator($request->only([
			'concept' => 'required',
			'amount' => 'required',
			'user_id' => 'required',
		]));

		if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}

		$outgoing->update($request->only([
			'concept',
			'amount',
			'user_id',
		]));

		return response()->json($outgoing, 200);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$outgoing = Outgoing::findOrFail($id);

		$outgoing->delete();

		return response()->json([
			"message" => "Outgoing deleted"
		], 200);
	}

	public function getByUser($id)
	{
		return Outgoing::where('user_id', $id)->get();
	}

	public function getByDate(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'start' => 'required',
			'end' => 'required',
			'order' => 'required',
		]));

		if ($validate->fails() || $request['start'] > $request['end'] || $request['start'] == null || $request['end'] == null)
			return response()->json([
				"message" => "An error has occurred"
			], 400);

		$start = Carbon::parse($request['start']);
		$end = Carbon::parse($request['end']);

		$sale = Outgoing::whereBetween('created_at', [$start, $end])->orderBy('created_at', $request['order'])->get();

		return response()->json($sale, 200);
	}

	public function getTotal(): \Illuminate\Http\JsonResponse
	{
		$total = Outgoing::sum('amount');
		return response()->json($total, 200);
	}

	public function getTotalByDate(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only([
			'start' => 'required',
			'end' => 'required',
		]));

		if ($validate->fails() || $request['start'] > $request['end'] || $request['start'] == null || $request['end'] == null)
			return response()->json([
				"message" => "An error has occurred"
			], 400);

		$start = Carbon::parse($request['start']);
		$end = Carbon::parse($request['end']);

		$total = Outgoing::whereBetween('created_at', [$start, $end])->sum('amount');
		return response()->json($total, 200);
	}
}
