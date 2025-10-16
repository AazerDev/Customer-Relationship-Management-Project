<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Http\Requests\MeetingRequest;
use App\Helpers\FileUploadHelper;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MeetingController extends Controller
{
    /**
     * Get all meetings
     */
    public function index(Request $request): JsonResponse
    {
        $query = Meeting::with(['lead', 'customers']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->per_page ?? 10;
        $meetings = $query->orderBy('date', 'desc')->orderBy('time', 'desc')->paginate($perPage);

        return apiSuccess($meetings, 'Meetings retrieved successfully');
    }

    /**
     * Create a new meeting
     */
    public function store(MeetingRequest $request): JsonResponse
    {
        $data = $request->validated();
        // Handle attachment
        if ($request->hasFile('attachment')) {
            $data['attachment'] = FileUploadHelper::uploadFile($request->file('attachment'), 'meetings');
        }

        // Create meeting
        $meeting = Meeting::create($data);

        // Send email to participants
        EmailHelper::sendMeetingNotification($meeting);
        return apiSuccess($meeting->load(['lead', 'customers']), 'Meeting created successfully', 201);
    }

    /**
     * Get a specific meeting
     */
    public function show($id): JsonResponse
    {
        $meeting = Meeting::with(['lead', 'customers'])->find($id);
        if (!$meeting) {
            return apiError('Meeting not found', 404);
        }
        return apiSuccess($meeting, 'Meeting retrieved successfully');
    }

    /**
     * Update a specific meeting
     */
    public function update(MeetingRequest $request, $id): JsonResponse
    {
        $data = $request->validated();
        $meeting = Meeting::findOrFail($id);

        // Handle attachment
        if ($request->hasFile('attachment')) {
            if ($meeting->attachment) {
                FileUploadHelper::deleteFile($meeting->attachment);
            }
            $data['attachment'] = FileUploadHelper::uploadFile($request->file('attachment'), 'meetings');
        }

        $meeting->update($data);

        // Send email to participants if participants changed
        if (isset($data['participants']) && $data['participants'] !== $meeting->participants) {
            EmailHelper::sendMeetingNotification($meeting);
        }
        return apiSuccess($meeting->load(['lead', 'customers']), 'Meeting updated successfully');
    }

    /**
     * Delete a specific meeting
     */
    public function destroy($id): JsonResponse
    {
        $meeting = Meeting::findOrFail($id);

        // Delete attachment if exists
        if ($meeting->attachment) {
            FileUploadHelper::deleteFile($meeting->attachment);
        }

        $meeting->delete();

        return apiSuccess(null, 'Meeting deleted successfully');
    }
}
