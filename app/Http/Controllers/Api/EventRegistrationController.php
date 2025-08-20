<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventRegistrationController extends Controller
{
    /**
     * Display a listing of registrations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $registrations = EventRegistration::with(['event', 'user'])
            ->when(request('event_id'), function ($query, $eventId) {
                return $query->where('event_id', $eventId);
            })
            ->when(request('user_id'), function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_order', 'desc'))
            ->paginate(request('per_page', 10));

        return ResponseFormatter::success($registrations, 'Registrations retrieved successfully');
    }

    /**
     * Store a new registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        // Check if event is still open for registration
        $event = Event::find($request->event_id);
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            return ResponseFormatter::error('Registration deadline has passed', 422);
        }

        $registration = EventRegistration::create(array_merge(
            $validator->validated(),
            ['user_id' => Auth::id()]
        ));

        return ResponseFormatter::success($registration->load(['event', 'user']), 'Registration created successfully', 201);
    }

    /**
     * Display the specified registration.
     *
     * @param  \App\Models\EventRegistration  $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(EventRegistration $registration)
    {
        return ResponseFormatter::success($registration->load(['event', 'user']), 'Registration retrieved successfully');
    }

    /**
     * Update the specified registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventRegistration  $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, EventRegistration $registration)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        $registration->update($validator->validated());

        return ResponseFormatter::success($registration->load(['event', 'user']), 'Registration updated successfully');
    }

    /**
     * Remove the specified registration.
     *
     * @param  \App\Models\EventRegistration  $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(EventRegistration $registration)
    {
        $registration->delete();

        return ResponseFormatter::success(null, 'Registration deleted successfully');
    }
}
