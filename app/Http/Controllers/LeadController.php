<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Http\Requests\LeadRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\FileUploadHelper;

class LeadController extends Controller
{
    /**
     * Get all leads
     */
    public function index(Request $request): JsonResponse
    {
        $query = Lead::with(['assignedUser', 'client']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Search in client
                $q->whereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });
        }

        // Filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('source')) {
            $query->where('source', $request->source);
        }


        // Pagination
        $perPage = $request->per_page ?? 10;
        $leads = $query->orderBy('created_at', 'desc')->paginate($perPage);
        return apiSuccess($leads, 'Leads retrieved successfully');
    }

    /**
     * To store or update lead
     */
    public function storeOrUpdate(LeadRequest $request, $id = null): JsonResponse
    {
        $data = $request->validated();

        // Handle tags if present
        if (isset($data['tags'])) {
            $data['tags'] = array_map('trim', $data['tags']);
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            if (!empty($id)) {
                $oldLead = Lead::find($id);
                if ($oldLead && $oldLead->file) {
                    FileUploadHelper::deleteFile($oldLead->file);
                }
            }
            $data['file'] = FileUploadHelper::uploadFile($request->file('file'), 'leads');
        }

        if ($id  && $id != null) {
            // Update existing lead
            $lead = Lead::findOrFail($id);
            $lead->update($data);
            $message = 'Lead updated successfully';
        } else {
            // Create new lead
            $lead = Lead::create($data);
            $message = 'Lead created successfully';
        }

        $lead->load('assignedUser', 'client');
        return apiSuccess($lead, $message, $id ? 200 : 201);
    }

    /**
     * show single lead
     */
    public function show($id): JsonResponse
    {
        $lead = Lead::with('assignedUser')->findOrFail($id);
        return apiSuccess($lead);
    }

    public function destroy($id): JsonResponse
    {
        $lead = Lead::findOrFail($id);

        // Delete file if exists
        if ($lead->file) {
            FileUploadHelper::deleteFile($lead->file);
        }

        $lead->delete();

        return apiSuccess(null, 'Lead deleted successfully');
    }

    /**
     * update last contacted column
     */
    public function updateLastContacted(Request $request, $id): JsonResponse
    {
        $request->validate([
            'last_contacted' => 'required|date'
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update(['last_contacted' => $request->last_contacted]);

        return apiSuccess($lead, 'Last contacted date updated successfully');
    }
    /**
     * update Lead Status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required'
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update(['status' => $request->status]);

        return apiSuccess($lead, 'Lead status updated successfully');
    }
     /**
     * Assign Lead to user
     */
    public function assignLead(Request $request, $id): JsonResponse
    {
        $request->validate([
            'assigned_to' => 'required'
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update(['assigned_to' => $request->assigned_to]);
        $lead->load('assignedUser');
        return apiSuccess($lead, 'Lead assigned to user successfully');
    }
    /**
     * get pipe line board page data
     */
    public function getPipeLineBoard(Request $request): JsonResponse
    {
        $leads = Lead::with('client:id,id,name')
            ->select('leads.*', \DB::raw('t.total'))
            ->join(
                \DB::raw('(SELECT status, MAX(id) as latest_id, COUNT(*) as total 
                        FROM leads 
                        WHERE deleted_at IS NULL 
                        GROUP BY status) as t'),
                function ($join) {
                    $join->on('leads.id', '=', 't.latest_id');
                }
            )
            ->orderByDesc('leads.id')
            ->limit(10)
            ->get();

        return apiSuccess($leads ?? null, 'Data fetched');
    }

    /**
     * get user specific leads
     */
    public function getMyLeads(Request $request): JsonResponse
    {
        $leads = Lead::where('assigned_to', auth()->id())->with('client')->get();

        return apiSuccess($leads, $leads->isEmpty() ? 'No leads found' : 'Data fetched');
    }
}
