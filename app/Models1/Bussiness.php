<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bussiness extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'avatar', 'user_id'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    /**
     * Get all of the users for the Bussiness
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all of the accounts for the Bussiness
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get all of the currencies for the Bussiness
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currencies()
    {
        return $this->hasMany(Currency::class);
    }

    /**
     * Get all of the modules for the Bussiness
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules_bussiness()
    {
        return $this->hasMany(ModuleBussinesses::class);
    }
}
