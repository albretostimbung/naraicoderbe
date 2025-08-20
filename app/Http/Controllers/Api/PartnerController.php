<?php

namespace App\Http\Controllers\Api;

use App\Models\Partner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    /**
     * Display a listing of partners.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $partners = Partner::when(request('partnership_type'), function($query, $type) {
                return $query->where('partnership_type', $type);
            })
            ->when(request('is_active'), function($query, $isActive) {
                return $query->where('is_active', $isActive);
            })
            ->when(request('is_featured'), function($query, $isFeatured) {
                return $query->where('is_featured', $isFeatured);
            })
            ->when(request('search'), function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_order', 'desc'))
            ->paginate(request('per_page', 10));

        return ResponseFormatter::success($partners, 'Partners retrieved successfully');
    }

    /**
     * Store a newly created partner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'website_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'partnership_type' => 'required|in:corporate,educational,government,startup',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        $partner = Partner::create($validator->validated());

        return ResponseFormatter::success($partner, 'Partner created successfully', 201);
    }

    /**
     * Display the specified partner.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Partner $partner)
    {
        return ResponseFormatter::success($partner, 'Partner retrieved successfully');
    }

    /**
     * Update the specified partner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Partner $partner)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'website_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'partnership_type' => 'sometimes|required|in:corporate,educational,government,startup',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        $partner->update($validator->validated());

        return ResponseFormatter::success($partner->fresh(), 'Partner updated successfully');
    }

    /**
     * Remove the specified partner.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return ResponseFormatter::success(null, 'Partner deleted successfully');
    }
}
