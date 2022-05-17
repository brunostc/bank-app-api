<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BalanceController extends Controller {

    public function getBalance(Request $request) {
        $this->validate($request, [
            'month' => 'nullable|string',
            'year' => 'nullable|integer',
        ]);

        $balance = Balance::where(['bal_user_id' => $request->user()->id])
            ->firstOrFail();

        $incomes = Transaction::where(['tra_balance_id' => $balance->id,
            'tra_type' => 'DEPOSIT'
        ])->sum('tra_amount');

        $expenses = Transaction::where(['tra_balance_id' => $balance->id,
            'tra_type' => 'CHARGE'
        ])->sum('tra_amount');

        $last_five_transactions = Transaction::with(['purchases', 'deposits'])
            ->where(['tra_balance_id' => $balance->id])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'balance' => $balance,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'last_five_transactions' => $last_five_transactions,
        ]);
    }
}
