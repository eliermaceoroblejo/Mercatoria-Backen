<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $unit_id
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property Unit $unit
 * @property MovementDetail[] $movementDetails
 * @property StoreProduct[] $storeProducts
 */
class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['unit_id', 'id'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movementDetails()
    {
        return $this->hasMany('App\Models\MovementDetail');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storeProducts()
    {
        return $this->hasMany('App\Models\StoreProduct');
    }
}
