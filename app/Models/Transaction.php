<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    protected $table = 'transactions';

    protected $fillable = [
        'tra_balance_id',
        'tra_type',
        'tra_amount',
        'tra_previous_balance',
        'tra_new_balance',
        'tra_description',
    ];

    public function balance() {
        return $this->belongsTo(Balance::class, 'tra_balance_id');
    }

    public function purchases() {
        return $this->hasMany(Purchase::class, 'pur_transaction_id');
    }

    public function deposits() {
        return $this->hasMany(Deposit::class, 'dep_transaction_id');
    }
}
