<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
	use HasFactory;

	protected $table = 'business';

	protected $fillable = [
		'name'
	];

	// One to many
	public function customers(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Customer::class);
	}

	// Many to many
	public function directions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Direction::class, 'directions_business');
	}
}
