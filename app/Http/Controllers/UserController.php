<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = User::with('wallet')
            ->find(Auth::user()->id);

        return response()->json($user);
    }


    /**
     * Find one user.
     *
     * @return Response
     */
    public function find(Request $request)
    {
        $field_rule = Rule::in(['id', 'document', 'email']);

        $request->merge([
            'field' => $request->get('field'),
            'value' => $request->get('value')
        ]);

        $this->validate($request, [
            'field' => ['required', $field_rule],
            'value' => 'required',
        ]);

        try {
            $user = User::where($request->field, '=', $request->value)->first();

            if (empty($user)) throw new \Exception();

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage() ?: 'user not found!'], 404);
        }

    }

}
