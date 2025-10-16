<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\FileUploadHelper;

class ClientController extends Controller
{
    /**
     * Get All Clients with filters
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'paginate' => 'required|in:true,false',

        ]);

        $query = Client::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Pagination
        if ($request->paginate == 'false') {
            $customers = $query->get();
        } else {
            $perPage = $request->per_page ?? 10;
            $customers = $query->orderBy('created_at', 'desc')->paginate($perPage);
        }
        return apiSuccess($customers, 'Clients retrieved successfully');
    }

    /**
     * Create or Update Client
     */
    public function storeOrUpdate(ClientRequest $request, $id = null): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['tags'])) {
            $data['tags'] = array_map('trim', $data['tags']);
        }

        $customer = $id ? Client::findOrFail($id) : new Client();

        if ($request->hasFile('profile_pic')) {
            if ($id && !empty($customer->profile_pic)) {
                FileUploadHelper::deleteFile($customer->profile_pic);
            }
            $data['profile_pic'] = FileUploadHelper::uploadFile($request->file('profile_pic'), 'client/profile');
        }

        $customer->fill($data)->save();

        return apiSuccess(
            $customer,
            $id ? 'Client updated successfully' : 'Client created successfully',
            $id ? 200 : 201
        );
    }

    /**
     * Get Single Client
     */
    public function show($id): JsonResponse
    {
        $customer = Client::find($id);
        if (!$customer) {
            return apiError('Client not found', 404);
        }
        return apiSuccess($customer, 'Client retrieved successfully');
    }

    /**
     * Delete Client
     */
    public function destroy($id): JsonResponse
    {
        $customer = Client::findOrFail($id);

        // Delete profile picture if exists
        if (!empty($customer->profile_pic)) {
            FileUploadHelper::deleteFile($customer->profile_pic);
        }

        $customer->delete();

        return apiSuccess(null, 'Client deleted successfully');
    }
}
