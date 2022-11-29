<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleBussinesses extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'bussiness_id', 'month', 'year'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    /**
     * Get all of the modules for the ModuleBussinesses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Get all of the bussiness for the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bussiness()
    {
        return $this->hasMany(Bussiness::class);
    }
}
