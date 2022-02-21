<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomerSale;
use App\Models\Outgoing;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function login(Request $request): \Illuminate\Http\JsonResponse
	{
		$data = [
			'nickname' => $request['nickname'],
			'password' => $request['password']
		];

		if (auth()->attempt($data)) {
			$token = auth()->user()->createToken('Laravel8Auth')->accessToken;
			$user = auth()->user();
			return response()->json(['token' => $token, 'user' => $user], 200);
		} else {
			return response()->json(['error' => 'Unauthorized'], 401);
		}
	}

	public function index()
	{
		return User::all();
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name', 'email', 'nickname', 'password'), [
			'name' => 'required|string',
			'email' => 'email|unique:users',
			'nickname' => 'required|string',
			'password' => 'required|string|min:5'
		]);

		if ($validate->fails()) {
			return response()->json($validate->errors()->all(), 400);
		}

		$data = $request->only('name', 'email', 'nickname', 'password', 'role');
		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'nickname' => $data['nickname'],
			'password' => bcrypt($data['password']),
			'role' => $data['role'] ? 'ADMIN' : 'USER'
		]);

		$token = $user->createToken('Laravel8Auth')->accessToken;
		isset($data['role']) ? $user->assignRole('admin') : $user->assignRole('user');

		return response()->json($user, 201);
	}

	public function show($id)
	{
		return User::where('id', $id)->get();
	}

	public function update(Request $request, $id): \Illuminate\Http\JsonResponse
	{
		$validate = validator($request->only('name', 'email', 'nickname', 'password'), [
			'name' => 'string',
			'email' => "email|unique:users,email,$id,id",
			'nickname' => 'string'
		]);

		if ($validate->fails()) {
			return response()->json($validate->errors()->all(), 400);
		}

		$user = User::findOrFail($id);
		$user->name = $request['name'];
		$user->email = $request['email'];
		$user->nickname = $request['nickname'];
		$user->save();

		return response()->json($user, 201);
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
	{
		$user = User::findOrFail($id);
		$user->delete();

		return response()->json(['message' => 'The user has been deleted'], 200);
	}


	public function getTotalSalesOfTheDay(): \Illuminate\Http\JsonResponse
	{
		$totalSales = 0;
		$sales = Sale::whereDate('created_at', Carbon::today())->get();
		$customer_sales = CustomerSale::whereDate('created_at', Carbon::today())->get();

		foreach($sales as $sale)
		{
			$totalSales += $sale->total;
		}

		foreach($customer_sales as $customer_sale)
		{
			$totalSales += $customer_sale->total;
		}

		return response()->json(['total' => $totalSales], 200);
	}


	public function getOutgoingsOfTheDay(): \Illuminate\Http\JsonResponse
	{
		$total = 0;
		$expenses = Outgoing::whereDate('created_at', Carbon::today())->get();

		foreach($expenses as $expense)
		{
			$total += $expense->amount;
		}

		return response()->json(['outgoings' => $total], 200);
	}

}
