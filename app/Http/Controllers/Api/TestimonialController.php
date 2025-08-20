<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $testimonials = Testimonial::with(['user'])
            ->when(request('is_featured'), function($query, $isFeatured) {
                return $query->where('is_featured', $isFeatured);
            })
            ->when(request('is_published'), function($query, $isPublished) {
                return $query->where('is_published', $isPublished);
            })
            ->when(request('rating'), function($query, $rating) {
                return $query->where('rating', $rating);
            })
            ->when(request('search'), function($query, $search) {
                return $query->where('content', 'like', "%{$search}%");
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_order', 'desc'))
            ->paginate(request('per_page', 10));

        return response()->json([
            'status' => 'success',
            'data' => $testimonials
        ]);
    }

    /**
     * Store a newly created testimonial.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'program_id' => 'nullable|exists:programs,id',
            'event_id' => 'nullable|exists:events,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimonial = Testimonial::create(array_merge(
            $validator->validated(),
            ['user_id' => Auth::id()]
        ));

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial created successfully',
            'data' => $testimonial->load('user')
        ], 201);
    }

    /**
     * Display the specified testimonial.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Testimonial $testimonial)
    {
        return response()->json([
            'status' => 'success',
            'data' => $testimonial->load('user')
        ]);
    }

    /**
     * Update the specified testimonial.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        // Only allow users to update their own testimonials unless they're an admin
        if ($testimonial->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'program_id' => 'nullable|exists:programs,id',
            'event_id' => 'nullable|exists:events,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimonial->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial updated successfully',
            'data' => $testimonial->fresh()->load('user')
        ]);
    }

    /**
     * Remove the specified testimonial.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Testimonial $testimonial)
    {
        // Only allow users to delete their own testimonials unless they're an admin
        if ($testimonial->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $testimonial->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial deleted successfully'
        ]);
    }

    /**
     * Get featured testimonials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured()
    {
        $testimonials = Testimonial::with('user')
            ->where('is_featured', true)
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->take(request('limit', 6))
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $testimonials
        ]);
    }
}
