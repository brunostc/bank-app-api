<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {

    protected $table = 'purchases';

    protected $fillable = [
        'pur_user_id',
        'pur_transaction_id',
        'pur_amount',
        'pur_description',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'pur_user_id');
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'pur_transaction_id');
    }
}
