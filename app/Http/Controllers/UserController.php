<?php

namespace App\Http\Controllers;

use App\User;
use App\Login;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required|unique:logins,user_name',
                'password' => 'required',
                'user_email_id' => 'nullable',
                'user_address' => 'nullable',
                'user_contact' => 'nullable',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), JsonResponse::HTTP_BAD_REQUEST);
            }

            DB::beginTransaction();

            $login = Login::create([
                'user_name' => $request->input('user_name'),
                'password' => Hash::make($request->input('password'))
            ]);

            if ($request->hasAny(['user_name', 'user_address', 'user_contact'])) {
                $login->users()->create($request->all());
            }

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'User logged in successfully.',
            ], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function get(Request $request)
    {
        try {
            return User::when($request->has('limit'), function ($q) use ($request) {
                $q->limit($request->input('limit'));
            })->when($request->has('skip'), function ($q) use ($request) {
                $q->skip($request->input('skip'));
            })->with('login')->get();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
