<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DepositController extends Controller {

    public function list(Request $request) {
        $deposits = Deposit::where(['dep_user_id' => $request->user()->id])->with('file')->orderBy('id', 'desc')->get();

        foreach($deposits as $deposit) {
            $deposit->amount_formatted = ' + US$ ' . number_format($deposit->dep_amount/100, 2, '.', ',');
            $deposit->approved_at_format = Carbon::parse($deposit->dep_approved_at)->format('d/m/y H:i a');
            $deposit->created_at_format = Carbon::parse($deposit->created_at)->format('d/m/y H:i a');
        }

        return response()->json($deposits);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'amount' => ['integer', 'required', 'max:100000000'],
            'check_image' => ['required', 'image', 'max:5120']
        ]);

        $file = Utils::addAttachment($request->file('check_image'));

        $deposit = new Deposit();

        $deposit->dep_user_id = $request->user()->id;
        $deposit->dep_file_id = $file->id;
        $deposit->dep_amount = $request->amount;

        $deposit->save();

        return response()->json($deposit->load('file'));
    }
}
