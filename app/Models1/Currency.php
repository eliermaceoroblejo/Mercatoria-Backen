<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $abbreviation
 * @property float $rate
 * @property string $created_at
 * @property string $updated_at
 * @property Account[] $accounts
 */
class Currency extends Model
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
    protected $fillable = ['name', 'abbreviation', 'rate', 'bussiness_id'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('App\Models\Account');
    }

    /**
     * Get all of the bussiness for the Currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bussiness()
    {
        return $this->hasMany(Bussiness::class);
    }
}
