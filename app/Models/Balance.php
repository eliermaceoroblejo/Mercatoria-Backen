<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $account_id
 * @property integer $bussiness_id
 * @property float $start_amount
 * @property float $debit
 * @property float $credit
 * @property float $amount
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property Bussiness $bussiness
 */
class Balance extends Model
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
    protected $fillable = ['account_id', 'bussiness_id', 'start_amount', 'debit', 'credit', 'amount', 'bussiness_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bussiness()
    {
        return $this->belongsTo('App\Models\Bussiness');
    }
}
