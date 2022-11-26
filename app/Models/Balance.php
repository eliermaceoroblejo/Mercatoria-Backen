<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $account_id
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 */
class Balance extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['account_id'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->hasOne('App\Models\Account');
    }
}
