<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemandRequest;
use App\Http\Requests\UpdateDemandStatusRequest;
use App\Models\Demand;
use App\Repository\DemandRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DemandController extends Controller
{
    private $demandRepository;

    public function __construct(DemandRepository $demandRepository)
    {
        $this->demandRepository = $demandRepository;
    }

    public function index(): JsonResponse
    {
        $demands = Demand::all();
        return response()->json($demands);
    }

    public function store(DemandRequest $request): JsonResponse
    {
        try {
            $demandData = $this->demandRepository->prepareDemandData($request);

            $demand = Demand::create($demandData);

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
    public function getDemandsByFreelancer($freelancerId): JsonResponse
    {
        try {
            $demands = Demand::where('freelancer_id', $freelancerId)
                ->with('client.user:id,email,first_name,last_name,phone')
                ->get();
            return response()->json($demands);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve demands for freelancer: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve demands'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDemandsByFreelancerApprouved($freelancerId): JsonResponse
    {
        try {
            $demands = Demand::where('freelancer_id', $freelancerId)
                ->where('approuval', 'Accepted')
                ->with('client.user:id,email,first_name,last_name,phone')
                ->get();
            return response()->json($demands);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve demands for freelancer: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve demands'], Response::HTTP_BAD_REQUEST);
        }
    }



    public function updateStatus(UpdateDemandStatusRequest $request, $id): JsonResponse
    {
        try {
            $demand = Demand::findOrFail($id);
            $demand->status = $request->input('status');
            $demand->save();

            return response()->json([
                'message' => trans('messages.status_updated'),
                'demand' => $demand]);
        } catch (\Exception $e) {
            Log::error('Failed to update demand status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update demand status'], Response::HTTP_NOT_FOUND);
        }
    }

    public function updateApprouval(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'approuval' => [
                'required',
                'string',
                'in:Accepted,Rejected,On Hold',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $demand = Demand::findOrFail($id);
            $demand->approuval = $request->input('approuval');
            $demand->save();

            return response()->json([
                'message' => 'Approuval status updated successfully',
                'demand' => $demand
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update approuval status: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update approuval status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(DemandRequest $request, $id): JsonResponse
    {
        try {
            $demand = Demand::findOrFail($id);
            $demandData = $this->demandRepository->prepareDemandData($request);

            $demand->update($demandData);

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
