<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $account_nature_id
 * @property integer $currency_id
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Balance[] $balances
 * @property AccountNature $accountNature
 * @property Currency $currency
 * @property OperationDetail[] $operationDetails
 */
class Account extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['account_nature_id', 'currency_id', 'id', 'name', 'account_type', 'account_group_id'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function balance()
    {
        return $this->hasOne('App\Models\Balance');
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
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operationDetails()
    {
        return $this->hasMany('App\Models\OperationDetail');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function accountType()
    {
        return $this->hasOne(AccountType::class, 'id', 'account_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
