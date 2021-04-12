<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AuthController extends Controller
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
     * Store a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $category_rule = Rule::in(['consumer', 'seller']);

        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'document' => 'required|cpf_ou_cnpj',
            'category' => [
                'required',
                $category_rule
            ],
            'password' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $user = User::create(
                [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'document' => $request->input('document'),
                    'category' => $request->input('category'),
                    'password' => app('hash')->make(
                        $request->input('password')
                    )
                ]
            );

            $wallet = Wallet::create(
                [
                    'user_id' => $user->id,
                    'amount' => 10000000
                ]
            );

            if ($user && $wallet) {
                DB::commit();
                //return successful
                return response()->json($user, 201);
            } else {
                throw new \Exception('User Registration Failed!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            //return error message
            return response()->json(
                ['message' => $e->getMessage() ?: 'User Registration Failed!'],
                409
            );
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function token(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

}
