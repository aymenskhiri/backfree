<?php

namespace App\Http\Controllers;

use App\Http\Requests\FreelancerRequest;
use App\Models\FreelancerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FreelancerProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FreelancerProfile  $freelancerProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FreelancerProfile $freelancerProfile): JsonResponse
    {
        return response()->json($freelancerProfile);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\FreelancerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FreelancerRequest $request): JsonResponse
    {
        try {
            $freelancerData = [
                'user_id' => $request->user_id,
                'bio' => $request->bio,
                'skills' => $request->skills,
                'hourly_price' => $request->hourly_price,
                'reviews' => $request->reviews ?? 0,
            ];

            $freelancerProfile = FreelancerProfile::create($freelancerData);

            return response()->json([
                'message' => trans('messages.freelancer_created'),
                'freelancerProfile' => $freelancerProfile,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Freelancer profile creation failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.freelancer_creation_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FreelancerRequest  $request
     * @param  \App\Models\FreelancerProfile  $freelancerProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FreelancerRequest $request, FreelancerProfile $freelancerProfile): JsonResponse
    {
        try {
            $freelancerProfile->update($request->validated());

            return response()->json([
                'message' => trans('messages.freelancer_updated'),
                'freelancerProfile' => $freelancerProfile,
            ]);
        } catch (\Exception $e) {
            Log::error('Freelancer profile update failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.freelancer_update_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FreelancerProfile  $freelancerProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FreelancerProfile $freelancerProfile): JsonResponse
    {
        try {
            $freelancerProfile->delete();

            return response()->json([
                'message' => trans('messages.freelancer_deleted'),
            ], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            Log::error('Freelancer profile deletion failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.freelancer_deletion_failed')], Response::HTTP_BAD_REQUEST);
        }
    }
}
