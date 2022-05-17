<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model {

    protected $table = 'deposits';

    protected $fillable = [
        'dep_user_id',
        'dep_file_id',
        'dep_transaction_id',
        'dep_amount',
        'dep_is_approved',
        'dep_approved_at',
        'dep_reproved_at',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'dep_user_id');
    }

    public function file() {
        return $this->belongsTo(File::class, 'dep_file_id');
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'dep_transaction_id');
    }
}
