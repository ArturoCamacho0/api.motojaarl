<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	use HasFactory;

	protected $fillable = [
		'key',
		'name',
		'description',
		'stock',
		'minimum',
		'category_id',
	];

	// Many to one
	public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	// Many to many
	public function sales(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(
			Sale::class,
			'sales_products',
			'sale_id',
			'product_key');
	}

	public function prices(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(
			Price::class,
			'products_prices',
			'price_id',
			'product_key');
	}

	public function customer_sales(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(CustomerSale::class, 'customer_sales_products');
	}

	public function purchases(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Purchase::class, 'products_purchases');
	}
}
