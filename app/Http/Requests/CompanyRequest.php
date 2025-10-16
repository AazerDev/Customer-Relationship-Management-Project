<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $companyId = $this->route('id');
        $userId = $this->input('user_id'); // if updating user

        return [
            // --- Company fields ---
            'company_name'       => 'required|string|max:255',
            'subdomain'          => ['required', 'string', 'regex:/^[a-zA-Z0-9-]+$/', 'max:50', Rule::unique('companies')->ignore($companyId)],
            'admin_email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone'              => ['nullable', 'string', 'max:255'],
            'ai_models'          => 'nullable|array',
            'ai_models.*'        => 'string|in:GPT-4,GPT-3.5,Claude,LLaMA,Gemini,Chatbot,Vision,Analytics',
            'feature_modules'    => 'nullable|array',
            'feature_modules.*'  => 'string',
            'status'             => 'nullable|in:pending,active,inactive',
            'role_id'            => 'nullable|exists:roles,id',
            'logo'               => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',

            // --- Admin user fields ---
            'name'               => 'required|string|max:255',
            'role_id'            => 'required|exists:roles,id',
            'password'           => $userId ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'subdomain.regex'     => 'Subdomain can only contain letters, numbers, and hyphens.',
            'subdomain.unique'    => 'This subdomain is already taken. Please choose another one.',
            'admin_email.unique'  => 'This email is already registered with another user.',
            'admin_email.email'   => 'Please use a valid work email address.',
        ];
    }

}
