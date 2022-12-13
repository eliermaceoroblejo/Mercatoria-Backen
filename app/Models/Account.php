<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $account_nature_id
 * @property integer $currency_id
 * @property integer $account_type
 * @property integer $account_group_id
 * @property integer $bussiness_id
 * @property integer $number
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property AccountGroup $accountGroup
 * @property AccountNature $accountNature
 * @property AccountType $accountType
 * @property Bussiness $bussiness
 * @property Currency $currency
 * @property Balance[] $balances
 * @property OperationDetail[] $operationDetails
 */
class Account extends Model
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
    protected $fillable = ['account_nature_id', 'currency_id', 'account_type', 'account_group_id', 'bussiness_id', 'number', 'name', 'locked', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountGroup()
    {
        return $this->belongsTo('App\Models\AccountGroup');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountNature()
    {
        return $this->belongsTo('App\Models\AccountNature');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountType()
    {
        return $this->belongsTo('App\Models\AccountType', 'account_type');
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
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balances()
    {
        return $this->hasMany('App\Models\Balance');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operationDetails()
    {
        return $this->hasMany('App\Models\OperationDetail');
    }

    /**
     * Get all of the clientOperation for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientOperation()
    {
        return $this->hasMany(ClientOperations::class);
    }

    /**
     * Get all of the clientOperations for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function clientOperations()
    {
        return $this->hasManyThrough(Client::class, ClientOperations::class, 'account_id', 'id', 'id');
    }
}
