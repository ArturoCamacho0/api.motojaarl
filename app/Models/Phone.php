<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
	use HasFactory;

	protected $fillable = [
		'name'
	];

	// Many to many
	public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(User::class, 'phones_users');
	}

	public function providers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Provider::class, 'phones_providers');
	}

	public function customers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Customer::class, 'phones_customers');
	}
}
