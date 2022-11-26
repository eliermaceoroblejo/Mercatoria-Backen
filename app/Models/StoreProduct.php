<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $store_id
 * @property integer $product_id
 * @property float $amount
 * @property float $price
 * @property float $total
 * @property string $created_at
 * @property string $updated_at
 * @property Product $product
 * @property Store $store
 */
class StoreProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['store_id', 'product_id', 'amount', 'price', 'total'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }
}
