<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemandRequest;
use App\Models\Demand;
use Illuminate\Http\JsonResponse;

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
                'message' => 'Demand created successfully',
                'demand' => $demand
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create demand',
                'error' => $e->getMessage()
            ], 400);
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
                'message' => 'Demand updated successfully',
                'demand' => $demand
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update demand',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $demand = Demand::findOrFail($id);
            $demand->delete();

            return response()->json(['message' => 'Demand deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete demand',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
