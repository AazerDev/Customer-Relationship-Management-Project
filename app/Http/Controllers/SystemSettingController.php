<?php

namespace App\Http\Controllers;

use App\Models\SystemSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class SystemSettingController extends Controller
{

    // GET setting by name
    public function getSingleSettings($name): JsonResponse
    {
        $setting = SystemSettings::where('settings_name', $name)->first();

        if (!$setting) {
            return apiSuccess(null, 'Setting not found', 404);
        }

        return apiSuccess($setting, 'Setting retrieved successfully');
    }

    // CREATE or UPDATE setting
    public function storeOrUpdate(Request $request, $name): JsonResponse
    {
        $request->validate([
            'settings_data' => 'required|array',
        ]);

        $setting = SystemSettings::updateOrCreate(
            ['settings_name' => $name],
            [
                'settings_name' => $name,
                'settings_data' => $request->settings_data
            ]
        );

        $msg =  $setting->wasRecentlyCreated ? 'Setting created successfully' : 'Setting updated successfully';
        $cd = $setting->wasRecentlyCreated ? 201 : 200;
        return apiSuccess($setting, $msg, $cd);
    }
}
