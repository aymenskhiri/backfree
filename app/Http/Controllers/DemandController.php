<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemandRequest;
use App\Models\Demand;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DemandController extends Controller
{

    public function index(): JsonResponse
    {
        $demands = Demand::all();
        return response()->json($demands);
    }

    public function store(DemandRequest $request): JsonResponse
    {
        try {
            $demand = Demand::create([
                'status' => $request->input('status'),
                'service_date' => $request->input('service_date'),
                'description' => $request->input('description'),
                'post_id' => $request->input('post_id'),
                'freelancer_id' => $request->input('freelancer_id'),
                'client_id' => $request->input('client_id'),
            ]);

            return response()->json([
                'message' => trans('messages.service_demand_created'),
                'demand' => $demand
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('messages.service_demand_creation_failed'),
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getDemandsByClient($clientId): JsonResponse
    {
        try {
            $demands = Demand::where('client_id', $clientId)->get();
            return response()->json($demands);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve demands for client: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve demands'], Response::HTTP_BAD_REQUEST);
        }
    }
    public function show($id): JsonResponse
    {
        $demand = Demand::findOrFail($id);
        return response()->json($demand);
    }

    public function update(DemandRequest $request, $id): JsonResponse
    {
        try {
            $demand = Demand::findOrFail($id);
            $demand->update([
                'status' => $request->input('status'),
                'service_date' => $request->input('service_date'),
                'description' => $request->input('description'),
                'post_id' => $request->input('post_id'),
                'freelancer_id' => $request->input('freelancer_id'),
                'client_id' => $request->input('client_id'),
            ]);

            return response()->json([
                'message' => trans('messages.service_demand_updated'),
                'demand' => $demand
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('messages.service_demand_update_failed'),
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $demand = Demand::findOrFail($id);
            $demand->delete();

            return response()->json([
                'message' => trans('messages.service_demand_deleted'),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('messages.service_demand_deletion_failed'),
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
