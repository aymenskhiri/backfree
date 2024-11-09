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

    public function getDemandsByClient(Request $request, $clientId): JsonResponse
    {
        try {
            $query = Demand::where('client_id', $clientId);

            // Search functionality
            if ($search = $request->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%")
                        ->orWhereDate('service_date', 'LIKE', "%{$search}%")
                        ->orWhere('begin_hour', 'LIKE', "%{$search}%");
                });
            }

            if ($approval = $request->query('approval')) {
                $query->where('approuval', $approval);
            }

            if ($status = $request->query('status')) {
                $query->where('status', $status);
            }

            if ($sortBy = $request->query('sort_by')) {
                $sortDirection = $request->query('sort_direction', 'asc'); // Default to ascending order
                $query->orderBy($sortBy, $sortDirection);
            }

            $demands = $query->with('freelancer.user:id,email,first_name,last_name,phone')->get();

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
    public function getDemandsByFreelancer(Request $request, $freelancerId): JsonResponse
    {
        try {
            $query = Demand::where('freelancer_id', $freelancerId);

            if ($search = $request->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%")
                        ->orWhereDate('service_date', 'LIKE', "%{$search}%")
                        ->orWhere('begin_hour', 'LIKE', "%{$search}%");
                });
            }

            $demands = $query->with('client.user:id,email,first_name,last_name,phone')->get();

            return response()->json($demands);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve demands for freelancer: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve demands'], Response::HTTP_BAD_REQUEST);
        }
    }


    public function getDemandsByFreelancerApprouved(Request $request, $freelancerId): JsonResponse
    {
        try {

            $query = Demand::where('freelancer_id', $freelancerId)
                ->where('approuval', 'Accepted');

            // Apply search filter
            if ($search = $request->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%")
                        ->orWhereDate('service_date', 'LIKE', "%{$search}%")
                        ->orWhere('begin_hour', 'LIKE', "%{$search}%");
                });
            }

            if ($status = $request->query('status')) {
                $query->where('status', $status);
            }

            $orderBy = $request->query('sort_by', 'service_date'); // default to 'service_date' if not provided
            $orderDirection = $request->query('sort_direction', 'asc'); // default to 'asc' if not provided

            $query->orderBy($orderBy, $orderDirection);


            $demands = $query->with('client.user:id,email,first_name,last_name,phone')->get();

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
            $demandData = $request->only(['description', 'service_date', 'begin_hour']);

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
