<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $bussiness_id
 * @property string $code
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Bussiness $bussiness
 * @property Movement[] $movements
 */
class Client extends Model
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
    protected $fillable = ['bussiness_id', 'code', 'name', 'slug'];

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
    public function movements()
    {
        return $this->hasMany('App\Models\Movement');
    }

    /**
     * Get all of the operations for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operations()
    {
        return $this->hasMany(ClientOperations::class, 'client_id', 'id');
    }

    /**
     * Get all of the entryAccountsProvider for the Bussiness
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entryAccountsProvider()
    {
        return $this->hasMany(EntryAccountsProviders::class);
    }
}
