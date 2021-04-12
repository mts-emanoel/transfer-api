<?php

namespace App\Http\Controllers;

use App\Events\TransactionNotificationEvent;
use App\Libs\AuthorizerAPI;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     */
    public function create(Request $request)
    {

        // validation: categories allowed
        $categories_allowed = Rule::in(['consumer']);

        $request->merge([
            'category' => Auth::user()->category
        ]);

        // validation: verify amount and user receiver exists
        $this->validate($request, [
            'amount' => 'required|integer|min:1',
            'user_receiver_id' => [
                'required',
                'exists:users,id'
            ],
            'category' => [
                $categories_allowed
            ]
        ]);

        // validation: not allowed to transfer to the same user
        if (Auth::user()->id == $request->input('user_receiver_id')) {
            return response()->json(
                ['message' => 'Not allowed to transfer to the same user'],
                406
            );
        }

        try {
            // validation: AuthorizerAPI
            $user_origin = User::find(Auth::user()->id);
            $authorizerApi = new AuthorizerAPI();
            if ($authorizerApi->consult($user_origin)->getStatusCode() != 200) {
                throw new \Exception('not allowed by the authorizer service');
            }

            // start transaction
            DB::beginTransaction();

            $wallet_user_origin = Wallet::where('user_id', '=', Auth::user()->id)->first();
            $wallet_user_receiver = Wallet::where('user_id', '=', $request->input('user_receiver_id'))->first();
            $amount = intval($request->input('amount'));

            if ($wallet_user_origin->amount < $amount) {
                throw new \Exception('insufficient funds');
            }

            // create transaction (pending)
            $transaction = Transaction::create(
                [
                    'user_origin_id' => $wallet_user_origin->user_id,
                    'user_receiver_id' => $wallet_user_receiver->user_id,
                    'amount' => $amount,
                    'status' => 'pending'
                ]
            );

            $wallet_user_origin->amount = intval($wallet_user_origin->amount) - intval($amount);
            $wallet_user_receiver->amount = intval($wallet_user_receiver->amount) + intval($amount);

            // update wallets
            if ($wallet_user_origin->save() && $wallet_user_receiver->save()) {
                $transaction->status = 'paid';

                // update transaction to 'paid'
                if ($transaction->save()) {

                    // commit transaction
                    DB::commit();

                    // notification
                    event(new TransactionNotificationEvent(
                        User::find($wallet_user_receiver->user_id),
                        $transaction
                    ));

                    // success
                    return response()->json($transaction, 201);
                } else {
                    throw new \Exception();
                }
            }

        } catch (\Exception $e) {
            // rollback transaction
            DB::rollBack();

            //return error message
            return response()->json(
                ['message' => $e->getMessage() ?: 'Transaction Failed!'],
                409
            );
        }
    }

}
