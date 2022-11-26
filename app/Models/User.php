<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

// use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $pin
 * @property string $pin_expired_in
 * @property string $email_verified_at
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Operation[] $operations
 * @property Movement[] $movements
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

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['pin', 'pin_expired_in', 'email_verified_at', 'remember_token', 'created_at', 'updated_at'];

}
