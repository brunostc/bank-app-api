<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Purchase;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller {

    public function list(Request $request) {
        $purchases = Purchase::where(['pur_user_id' => $request->user()->id])->orderBy('id', 'desc')->get();

        foreach($purchases as $purchase) {
            $purchase->amount_formatted = ' - US$ ' . number_format($purchase->pur_amount/100, 2, '.', ',');
            $purchase->created_at_format = Carbon::parse($purchase->created_at)->format('d/m/y H:i a');
        }

        return response()->json($purchases);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'amount' => ['integer', 'required', 'max:100000000', 'min:0'],
            'description' => ['required', 'string', 'max:240']
        ]);

        $balance = Balance::where(['bal_user_id' => $request->user()->id])
            ->first();

        $balance_after_purchase =
            $balance->bal_current_balance - $request->amount;

        if ($balance_after_purchase < 0) {
            return response()->json(['errors' => ['Insufficient funds.']], 400);
        }

        $transaction = new Transaction();

        $transaction->tra_balance_id = $balance->id;
        $transaction->tra_amount = $request->amount;
        $transaction->tra_previous_balance = $balance->bal_current_balance;
        $transaction->tra_new_balance = $balance_after_purchase;
        $transaction->tra_description = $request->description;
        $transaction->tra_type = 'CHARGE';

        $transaction->save();

        $purchase = new Purchase();

        $purchase->pur_user_id = $request->user()->id;
        $purchase->pur_transaction_id = $transaction->id;
        $purchase->pur_amount = $request->amount;
        $purchase->pur_description = $request->description;

        $purchase->save();

        $balance->bal_current_balance = $balance_after_purchase;

        $balance->save();

        return response()->json($purchase);
    }
}
