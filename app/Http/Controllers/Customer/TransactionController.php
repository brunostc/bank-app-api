<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller {

    public function list(Request $request) {
        $transactions = Transaction::with(['purchases', 'deposits'])
            ->whereHas('balance',function ($balance) use ($request) {
                $balance->whereHas('user', function ($user) use ($request) {
                    $user->where('id', $request->user()->id);
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        foreach ($transactions as $transaction) {
            $type = $transaction->tra_type == 'CHARGE' ? ' - ' : '';

            $transaction->amount_formatted = $type . 'US$ ' . number_format($transaction->tra_amount/100, 2, '.', ',');
            $transaction->old_amount_formatted = 'US$ ' . number_format($transaction->tra_previous_balance/100, 2, '.', ',');
            $transaction->new_amount_formatted = 'US$ ' . number_format($transaction->tra_new_balance/100, 2, '.', ',');
            $transaction->created_at_format = Carbon::parse($transaction->created_at)->format('d/m/y H:i a');
        }

        return response()->json($transactions);
    }
}
