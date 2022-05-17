<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    public function get(Request $request) {
        $whereNull = [
            'dep_transaction_id',
            'dep_approved_at',
            'dep_reproved_at',
        ];

        return response()->json([
            'number_of_pending_deposits' => Deposit::whereNull($whereNull)->count()
        ]);
    }
}
