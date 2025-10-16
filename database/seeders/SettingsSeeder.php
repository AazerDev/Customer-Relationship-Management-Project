<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // Profile Settings
        Setting::setValue('full_name', 'Jane Cooper', 'profile');
        Setting::setValue('email', 'jane@company.com', 'profile');
        Setting::setValue('role', 'Sales Manager', 'profile');
        Setting::setValue('phone', '+1 202 555 0136', 'profile');
        Setting::setValue('timezone', 'GMT-5 (Eastern)', 'profile');
        Setting::setValue('language', 'English', 'profile');

        // Business Settings
        Setting::setValue('company_name', 'Acme Inc.', 'business');
        Setting::setValue('website', 'https://acme.co', 'business');
        Setting::setValue('industry', 'SaaS', 'business');
        Setting::setValue('employees', '51-200', 'business');
        Setting::setValue('address', '500 Howard St, San Francisco, CA', 'business');
        Setting::setValue('billing_email', 'billing@acme.co', 'business');

        // Notification Settings
        Setting::setValue('new_lead_assigned', true, 'notifications');
        Setting::setValue('lead_moved_stage', true, 'notifications');
        Setting::setValue('task_due_today', true, 'notifications');
        Setting::setValue('weekly_summary_email', true, 'notifications');
        Setting::setValue('digest_frequency', 'Weekly', 'notifications');
        Setting::setValue('quiet_hours_start', '21:00', 'notifications');
        Setting::setValue('quiet_hours_end', '07:00', 'notifications');

        // API Keys (empty initially)
        Setting::setValue('api_keys', [], 'api_keys');

        // Custom Fields (example)
        Setting::setValue('custom_fields', [
            [
                'field_name' => 'Budget',
                'type' => 'number',
                'applies_to' => 'leads',
                'required' => true,
                'options' => null
            ]
        ], 'custom_fields');
    }
}
