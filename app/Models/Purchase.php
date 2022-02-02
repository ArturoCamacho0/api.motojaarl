<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	use HasFactory;

	// Many to one
	public function provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Provider::class);
	}

	// Many to many
	public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Product::class, 'products_purchases');
	}
}
