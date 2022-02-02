<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
	use HasApiTokens, Notifiable, HasRoles;

	protected $fillable = [
		'name',
		'email',
		'nickname',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	// Has many
	public function outgoings(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Outgoing::class);
	}

	public function customer_sales(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(CustomerSale::class);
	}

	public function sales(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Sale::class);
	}

	// Many to many
	public function phones(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Phone::class, 'phones_users');
	}
}
