<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UsersController extends Controller
{
    public function index()
    {
        return view('supper-admin.users.user-list');
    }

    public function create()
    {
        return view('supper-admin.users.user-create');
    }


    /**
     * Update user profile  
     */
    public function updateProfile(Request $request): JsonResponse
    {
        // $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user_id)],
            'role_id' => ['nullable', 'exists:roles,id'],
            'bio' => ['nullable', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'language' => ['nullable', 'string', 'max:10'],
        ]);


        if ($validator->fails()) {
            return apiError('Validation failed', 422, $validator->errors());
        }

        try {
            $fields = ['role_id', 'name', 'phone', 'timezone', 'language', 'bio'];
            $data = [];

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $data[$field] = $request->input($field);
                }
            }

            User::where('id', $request->user_id)->update($data);
            return apiSuccess(null, 'Profile updated successfully');
        } catch (\Exception $e) {
            return apiError('Failed to update', 500, $e->getMessage());
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return apiError('Validation failed', 422, $validator->errors());
        }

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return apiError('Current password is incorrect', 422);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return apiSuccess(null, 'Password updated successfully');
        } catch (\Exception $e) {
            return apiError('Failed to update password', 500, $e->getMessage());
        }
    }
}
