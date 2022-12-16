<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $bussiness_id
 * @property string $name
 * @property string $abbreviation
 * @property boolean $unitary
 * @property string $created_at
 * @property string $updated_at
 * @property Bussiness $bussiness
 * @property Product[] $products
 */
class Unit extends Model
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
    protected $fillable = ['bussiness_id', 'name', 'abbreviation', 'unitary', 'slug'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bussiness()
    {
        return $this->belongsTo('App\Models\Bussiness');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
