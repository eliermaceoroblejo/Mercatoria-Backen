<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $bussiness_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Bussiness $bussiness
 * @property StoreProduct[] $storeProducts
 * @property Movement[] $movements
 */
class Store extends Model
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
    protected $fillable = ['bussiness_id', 'name', 'slug'];

    protected $hidden = ['slug', 'created_at', 'updated_at'];

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
    public function storeProducts()
    {
        return $this->hasMany('App\Models\StoreProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movements()
    {
        return $this->hasMany('App\Models\Movement');
    }

    /**
     * Get all of the accounts for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get all of the storeAccount for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storeAccount()
    {
        return $this->hasMany(StoreAccounts::class);
    }
}
