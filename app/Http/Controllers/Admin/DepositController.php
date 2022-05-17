<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DepositController extends Controller {

    public function listPending(Request $request) {
        $deposits = Deposit::with(['file', 'user'])
            ->where(['dep_is_approved' => 0])
            ->whereNull([
                'dep_transaction_id',
                'dep_approved_at',
                'dep_reproved_at',
            ])
            ->orderBy('id', 'desc')
            ->get();

        foreach($deposits as $deposit) {
            $deposit->amount_formatted = ' + US$ ' . number_format($deposit->dep_amount/100, 2, '.', ',');
            $deposit->approved_at_format = Carbon::parse($deposit->dep_approved_at)->format('d/m/y H:i a');
            $deposit->created_at_format = Carbon::parse($deposit->created_at)->format('d/m/y H:i a');
        }

        return response()->json($deposits);
    }

    public function approve(Request $request, $id) {
        $deposit = Deposit::where(['id' => $id])
            ->whereNull([
                'dep_transaction_id',
                'dep_approved_at',
                'dep_reproved_at',
            ])
            ->firstOrFail();

        $balance = Balance::where(['bal_user_id' => $deposit->dep_user_id])
            ->firstOrFail();

        $new_balance = $balance->bal_current_balance + $deposit->dep_amount;

        $transaction = new Transaction();

        $transaction->tra_balance_id = $balance->id;
        $transaction->tra_amount = $deposit->dep_amount;
        $transaction->tra_previous_balance = $balance->bal_current_balance;
        $transaction->tra_new_balance = $new_balance;
        $transaction->tra_description = 'Check deposit';
        $transaction->tra_type = 'DEPOSIT';

        $transaction->save();

        $deposit->dep_is_approved = 1;
        $deposit->dep_approved_at = Carbon::now();
        $deposit->dep_transaction_id = $transaction->id;

        $deposit->save();

        $balance->bal_current_balance = $new_balance;
        $balance->save();

        return response()->json($deposit);
    }

    public function reprove(Request $request, $id) {
        $deposit = Deposit::where(['id' => $id])
            ->whereNull([
                'dep_transaction_id',
                'dep_approved_at',
                'dep_reproved_at',
            ])
            ->firstOrFail();

        $deposit->dep_is_approved = 0;
        $deposit->dep_reproved_at = Carbon::now();

        $deposit->save();

        return response()->json($deposit);
    }
}
