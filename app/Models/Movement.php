<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $movement_type_id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $client_id
 * @property float $total
 * @property string $created_at
 * @property string $updated_at
 * @property MovementDetail[] $movementDetails
 * @property Client $client
 * @property MovementType $movementType
 * @property Store $store
 * @property User $user
 */
class Movement extends Model
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
    protected $fillable = ['movement_type_id', 'store_id', 'user_id', 'client_id', 'total'];

    protected $hidden = ['created_at', 'updated_at'];

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
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
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
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
