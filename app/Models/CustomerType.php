<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
	use HasFactory;

	protected $fillable = [
		'name'
	];

	public function customers(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Customer::class);
	}
}
