<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $events = Event::with(['eventRegistrations'])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('event_type'), function($query, $type) {
                return $query->where('event_type', $type);
            })
            ->when(request('search'), function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_order', 'desc'))
            ->paginate(request('per_page', 10));

        return ResponseFormatter::success($events, 'Events retrieved successfully');
    }

    /**
     * Store a newly created event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'event_type' => 'required|in:bootcamp,workshop,seminar,mentoring,meetup',
            'status' => 'required|in:draft,published,cancelled,completed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'nullable|string',
            'is_online' => 'boolean',
            'meeting_link' => 'nullable|string|url',
            'max_participants' => 'nullable|integer|min:1',
            'registration_fee' => 'required|numeric|min:0',
            'registration_deadline' => 'nullable|date|before:start_date'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        $event = Event::create(array_merge(
            $validator->validated(),
            [
                'organizer_id' => Auth::id(),
                'created_by' => Auth::id()
            ]
        ));

        return ResponseFormatter::success($event->load('eventRegistrations'), 'Event created successfully', 201);
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Event $event)
    {
        return ResponseFormatter::success($event->load('eventRegistrations'), 'Event retrieved successfully');
    }

    /**
     * Update the specified event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'event_type' => 'sometimes|required|in:bootcamp,workshop,seminar,mentoring,meetup',
            'status' => 'sometimes|required|in:draft,published,cancelled,completed',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'location' => 'nullable|string',
            'is_online' => 'boolean',
            'meeting_link' => 'nullable|string|url',
            'max_participants' => 'nullable|integer|min:1',
            'registration_fee' => 'sometimes|required|numeric|min:0',
            'registration_deadline' => 'nullable|date|before:start_date'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        $event->update($validator->validated());

        return ResponseFormatter::success($event->fresh()->load('eventRegistrations'), 'Event updated successfully');
    }

    /**
     * Remove the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return ResponseFormatter::success(null, 'Event deleted successfully');
    }

    /**
     * Get registrations for a specific event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrations(Event $event)
    {
        $registrations = $event->eventRegistrations()
            ->with('user')
            ->paginate(request('per_page', 10));

        return ResponseFormatter::success($registrations, 'Event registrations retrieved successfully');
    }
}
