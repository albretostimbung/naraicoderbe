<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of settings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings = Setting::when(request('group'), function($query, $group) {
                return $query->where('group', $group);
            })
            ->when(request('type'), function($query, $type) {
                return $query->where('type', $type);
            })
            ->when(request('search'), function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('key', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('key')
            ->get()
            ->groupBy('group');

        return ResponseFormatter::success($settings, 'Settings retrieved successfully');
    }

    /**
     * Store a newly created setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:settings',
            'value' => 'required|string',
            'group' => 'required|string|max:255',
            'type' => 'required|in:string,number,boolean,json',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        // Validate value based on type
        $validationError = $this->validateValueByType(
            $request->value,
            $request->type
        );

        if ($validationError) {
            return ResponseFormatter::error($validationError, 422);
        }

        $setting = Setting::create($validator->validated());

        return ResponseFormatter::success($setting, 'Setting created successfully', 201);
    }

    /**
     * Display the specified setting.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Setting $setting)
    {
        return ResponseFormatter::success($setting, 'Setting retrieved successfully');
    }

    /**
     * Update the specified setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Setting $setting)
    {
        $validator = Validator::make($request->all(), [
            'key' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('settings')->ignore($setting->id)],
            'value' => 'sometimes|required|string',
            'group' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:string,number,boolean,json',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        // If type is being updated, validate the existing or new value
        $type = $request->type ?? $setting->type;
        $value = $request->value ?? $setting->value;
        
        $validationError = $this->validateValueByType($value, $type);
        if ($validationError) {
            return ResponseFormatter::error($validationError, 422);
        }

        $setting->update($validator->validated());

        return ResponseFormatter::success($setting->fresh(), 'Setting updated successfully');
    }

    /**
     * Remove the specified setting.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return ResponseFormatter::success(null, 'Setting deleted successfully');
    }

    /**
     * Validate setting value based on its type.
     *
     * @param  string  $value
     * @param  string  $type
     * @return string|null
     */
    private function validateValueByType($value, $type)
    {
        switch ($type) {
            case 'number':
                if (!is_numeric($value)) {
                    return 'Value must be a number';
                }
                break;
            
            case 'boolean':
                if (!in_array(strtolower($value), ['true', 'false', '1', '0'])) {
                    return 'Value must be a boolean';
                }
                break;
            
            case 'json':
                json_decode($value);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return 'Value must be a valid JSON string';
                }
                break;
        }

        return null;
    }
}
