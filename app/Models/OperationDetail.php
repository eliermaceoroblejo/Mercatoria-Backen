<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $operation_id
 * @property integer $account_id
 * @property integer $module_id
 * @property float $credit
 * @property float $debit
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property Module $module
 * @property Operation $operation
 */
class OperationDetail extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['operation_id', 'account_id', 'module_id', 'credit', 'debit'];

    protected $hidden = ['created_at', 'updated_at'];

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
    public function module()
    {
        return $this->belongsTo('App\Models\Module');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operation()
    {
        return $this->belongsTo('App\Models\Operation');
    }
}
