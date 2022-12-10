<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $modules_id
 * @property integer $user_id
 * @property integer $bussiness_id
 * @property float $total_debit
 * @property float $total_credit
 * @property string $created_at
 * @property string $updated_at
 * @property Bussiness $bussiness
 * @property Module $module
 * @property User $user
 * @property OperationDetail[] $operationDetails
 */
class Operation extends Model
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
    protected $fillable = ['module_id', 'user_id', 'bussiness_id', 'total_debit', 'total_credit'];

    protected $hidden = ['updated_at'];

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
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operationDetails()
    {
        return $this->hasMany('App\Models\OperationDetail');
    }
}
