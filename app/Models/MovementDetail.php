<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $movement_id
 * @property integer $movement_type_id
 * @property integer $product_id
 * @property float $amount
 * @property float $price
 * @property float $total
 * @property string $created_at
 * @property string $updated_at
 * @property Movement $movement
 * @property MovementType $movementType
 * @property Product $product
 */
class MovementDetail extends Model
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
    protected $fillable = ['movement_id', 'movement_type_id', 'product_id', 'account_id', 'quantity', 'price', 'total'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movement()
    {
        return $this->belongsTo('App\Models\Movement');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movementType()
    {
        return $this->belongsTo('App\Models\MovementType');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
