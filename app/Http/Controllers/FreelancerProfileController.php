<?php

namespace App\Http\Controllers;

use App\Http\Requests\FreelancerRequest;
use App\Models\FreelancerProfile;
use App\Repository\FreelancerProfileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FreelancerProfileController extends Controller
{
    private $freelancerProfileRepository;

    public function __construct(\App\Repository\FreelancerProfileRepository $freelancerProfileRepository)
    {
        $this->freelancerProfileRepository = $freelancerProfileRepository;
    }

    public function show(FreelancerProfile $freelancerProfile): JsonResponse
    {
        return response()->json($freelancerProfile);
    }

    public function store(FreelancerRequest $request): JsonResponse
    {
        try {
            $freelancerData = $this->freelancerProfileRepository->prepareFreelancerData($request);

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

    public function update(FreelancerRequest $request, FreelancerProfile $freelancerProfile): JsonResponse
    {
        try {
            $freelancerData = $this->freelancerProfileRepository->prepareFreelancerData($request);

            $freelancerProfile->update($freelancerData);

            return response()->json([
                'message' => trans('messages.freelancer_updated'),
                'freelancerProfile' => $freelancerProfile,
            ]);
        } catch (\Exception $e) {
            Log::error('Freelancer profile update failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.freelancer_update_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

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
