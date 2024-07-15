<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceDemandRequest;
use App\Models\ServiceDemand;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ServiceDemandController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ServiceDemandRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ServiceDemandRequest $request): JsonResponse
    {
        try {
            $serviceDemand = ServiceDemand::create($request->validated());

            return response()->json([
                'message' => trans('messages.service_demand_created'),
                'service_demand' => $serviceDemand
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Service demand creation failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.service_demand_creation_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ServiceDemandRequest  $request
     * @param  \App\Models\ServiceDemand  $serviceDemand
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ServiceDemandRequest $request, ServiceDemand $serviceDemand): JsonResponse
    {
        try {
            $serviceDemand->update($request->validated());

            return response()->json([
                'message' => trans('messages.service_demand_updated'),
                'service_demand' => $serviceDemand
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Service demand update failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.service_demand_update_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceDemand  $serviceDemand
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ServiceDemand $serviceDemand): JsonResponse
    {
        try {
            $serviceDemand->delete();

            return response()->json([
                'message' => trans('messages.service_demand_deleted')
            ], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            Log::error('Service demand deletion failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.service_demand_deletion_failed')], Response::HTTP_BAD_REQUEST);
        }
    }
}
