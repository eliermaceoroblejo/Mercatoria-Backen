<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAccounts extends Model
{
    use HasFactory;

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    protected $fillable = ['bussiness_id', 'store_id', 'account_id'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get all of the bussiness for the StoreAccounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bussiness()
    {
        return $this->hasMany(Bussiness::class);
    }

    /**
     * Get all of the store for the StoreAcounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get all of the account for the StoreAcounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
