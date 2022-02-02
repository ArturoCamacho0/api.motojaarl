<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
	use HasFactory;

	protected $fillable = [
		'street',
		'number',
		'state',
		'cp'
	];

	// One to many
	public function providers(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Provider::class);
	}

	// Many to many
	public function business(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Business::class, 'directions_business');
	}
}
