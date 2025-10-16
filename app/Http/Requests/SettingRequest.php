<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
            'employees' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'billing_email' => 'nullable|email|max:255',
            'notification_preferences' => 'nullable|array',
            'notification_preferences.*' => 'boolean',  
            'notification_channels' => 'nullable|array',
            'notification_channels.*' => 'boolean', 
            'digest_frequency' => 'nullable|in:Daily,Weekly,Monthly',
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
            'custom_fields' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'language.exists' => 'The selected language is invalid.',
            'employees.integer' => 'The employees field must be a number.',
            'employees.min' => 'The employees field must be at least 1.',
            'notification_preferences.*.boolean' => 'Notification preference values must be true or false.',
            'notification_channels.*.boolean' => 'Notification channel values must be true or false.',
            'digest_frequency.in' => 'The digest frequency must be daily, weekly, or monthly.',
            'quiet_hours_start.date_format' => 'The quiet hours start time must be in H:i format (e.g., 22:00).',
            'quiet_hours_end.date_format' => 'The quiet hours end time must be in H:i format (e.g., 08:00).',
        ];
    }
    
}