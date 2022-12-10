<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOperations extends Model
{
    use HasFactory;

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    protected $fillable = ['client_id', 'movement', 'reference', 'account_id', 'bussiness_id'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get the client that owns the ClientOperations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the accounts that owns the ClientOperations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accounts()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the bussiness that owns the ClientOperations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bussiness()
    {
        return $this->belongsTo(Bussiness::class);
    }
}
