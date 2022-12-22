<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $store_id
 * @property integer $product_id
 * @property integer $bussiness_id
 * @property float $amount
 * @property float $price
 * @property float $total
 * @property string $created_at
 * @property string $updated_at
 * @property Bussiness $bussiness
 * @property Product $product
 * @property Store $store
 */
class StoreProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['store_id', 'product_id', 'account_id', 'bussiness_id', 'amount', 'price', 'sale_price', 'total'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bussiness()
    {
        return $this->belongsTo('App\Models\Bussiness');
    }

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

    /**
     * Get the account that owns the StoreProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
