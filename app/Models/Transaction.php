<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_origin_id', 'user_receiver_id', 'amount', 'status'
    ];

    /**
     * User origin (payer)
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function origin()
    {
        return $this->hasOne(\App\Models\User::class);
    }

    /**
     * User receiver
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receiver()
    {
        return $this->hasOne(\App\Models\User::class);
    }

}
