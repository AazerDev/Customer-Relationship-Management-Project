<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\SettingRequest;
use App\Models\Language;
use App\Models\TimeZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;


class SettingController extends Controller
{
    /**
     * get auth user settings
     */
    public function index(): JsonResponse
    {
        $settings = Setting::with('user','company')->where('user_id', Auth::id())->first();

        if (!$settings) {
            $settings = Setting::create(['user_id' => Auth::id()]);
            $settings->load('user');
        }

        return apiSuccess($settings, 'Settings retrieved successfully');
    }

    /**
     * Store and update settings for user
     */
    public function storeOrUpdate(SettingRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        $settings = Setting::where('user_id', $user->id)->first();

        if ($settings) {
            $settings->update($data);
            $message = 'Settings updated successfully';
        } else {
            $data['user_id'] = $user->id;
            $settings = Setting::create($data);
            $message = 'Settings created successfully';
        }

        $settings->load('user');

        return apiSuccess($settings, $message, $settings->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Update notification preferences in settings
     */
    public function updateNotificationPreferences(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notification_preferences' => 'sometimes|array',
            'notification_preferences.*' => 'boolean', 
            'notification_channels' => 'sometimes|array',
            'notification_channels.*' => 'boolean', 
            'quiet_hours_start' => 'sometimes|date_format:H:i',
            'quiet_hours_end' => 'sometimes|date_format:H:i'
        ]);

        $settings = Setting::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated // Use all validated data
        );

        return apiSuccess($settings, 'Notification preferences updated successfully');
    }

    /**
     * add custom field in user settings
     */
    public function addCustomField(Request $request): JsonResponse
    {
        $request->validate([
            'field_name' => 'required|string|max:255',
            'type' => 'required|in:Text,Number,Email,Phone,Date,Select',
            'applies_to' => 'required|in:Leads,Contacts,Both',
            'required' => 'required|boolean',
            'options' => 'nullable|array'
        ]);

        $settings = Setting::where('user_id', Auth::id())->firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $customFields = $settings->custom_fields ?? [];

        $newField = [
            'id' => uniqid(),
            'field_name' => $request->field_name,
            'type' => $request->type,
            'applies_to' => $request->applies_to,
            'required' => $request->required,
            'options' => $request->options,
            'created_at' => now()
        ];

        $customFields[] = $newField;

        $settings->update(['custom_fields' => $customFields]);

        return apisuccess($newField, 'Custom field added successfully', 201);
    }

    /**
     * delete custom field of user settings
     */
    public function deleteCustomField($fieldId): JsonResponse
    {
        $settings = Setting::where('user_id', Auth::id())->first();

        if (!$settings || !$settings->custom_fields) {
            return apisuccess(null, 'No custom fields found', 404);
        }

        $customFields = collect($settings->custom_fields)
            ->reject(function ($field) use ($fieldId) {
                return $field['id'] === $fieldId;
            })
            ->values()
            ->toArray();

        $settings->update(['custom_fields' => $customFields]);
        return apiSuccess(null, 'Custom field deleted successfully');
    }

    /**
     * search timezone from database (added by seeder)
     */
    function searchTimezone(Request $request)
    {
        try {
            $search = $request->query('search');
            $timezones = TimeZone::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->get();
            return apiSuccess($timezones, 'Timezones fetched successfully.', 200);
        } catch (Exception $e) {
            return apiError('Failed to fetch timezones', 500, $e->getMessage());
        }
    }

    /**
     * get all languages
     */
    public function getLanguages()
    {
        return Language::all();
    }
}
