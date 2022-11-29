<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $unit_id
 * @property integer $bussiness_id
 * @property integer $code
 * @property string $created_at
 * @property string $updated_at
 * @property StoreProduct[] $storeProducts
 * @property MovementDetail[] $movementDetails
 * @property Bussiness $bussiness
 * @property Unit $unit
 */
class Product extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['unit_id', 'bussiness_id', 'code', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storeProducts()
    {
        return $this->hasMany('App\Models\StoreProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movementDetails()
    {
        return $this->hasMany('App\Models\MovementDetail');
    }

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
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
}
