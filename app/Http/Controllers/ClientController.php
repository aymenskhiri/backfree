<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    /**
     * Store a newly created client in storage.
     *
     * @param  \App\Http\Requests\ClientRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ClientRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            //repo
            $arr = $this->getArrAttStoreClient($validatedData);
            $client = Client::create($arr);

            return response()->json([
                'message' => trans('messages.client_created_successfully'),
                'client' => $client
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Client account creation failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.client_creation_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Client $client): JsonResponse
    {
        return response()->json($client);
    }

    /**
     * Update the specified client in storage.
     *
     * @param  \App\Http\Requests\ClientRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ClientRequest $request, Client $client): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $client->update([
                'user_id' => $validatedData['user_id'],
                'demands_history' => $validatedData['demands_history'] ?? $client->demands_history,
            ]);

            return response()->json([
                'message' => trans('messages.client_updated_successfully'),
                'client' => $client
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Client update failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.client_update_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Client $client): JsonResponse
    {
        try {
            $client->delete();

            return response()->json([
                'message' => trans('messages.client_deleted_successfully')
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Client deletion failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.client_deletion_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param mixed $validatedData
     * @return array
     */
    public function getArrAttStoreClient(mixed $validatedData): array
    {
        $arr = [
            'user_id' => $validatedData['user_id'],
            'demands_history' => $validatedData['demands_history'] ?? null,
        ];
        return $arr;
    }
}
