<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model {

    protected $table = 'balances';

    protected $fillable = [
        'bal_user_id',
        'bal_current_balance',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'bal_user_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'tra_balance_id');
    }
}
