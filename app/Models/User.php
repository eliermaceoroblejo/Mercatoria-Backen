<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $pin
 * @property string $pin_expired_in
 * @property string $email_verified_at
 * @property integer $current_bussiness
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Bussiness[] $bussinesses
 * @property Movement[] $movements
 * @property Operation[] $operations
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    protected $fillable = ['name', 'email', 'password', 'bussiness_id'];

    protected $hidden = ['pin', 'pin_expired_in', 'password', 'email_verified_at', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bussinesses()
    {
        return $this->hasMany('App\Models\Bussiness');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movements()
    {
        return $this->hasMany('App\Models\Movement');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operations()
    {
        return $this->hasMany('App\Models\Operation');
    }
}
