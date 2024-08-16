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

    public function index()
    {
        try {
            $freelancers = FreelancerProfile::all();
            return response()->json($freelancers);
        } catch (\Exception $e) {
            Log::error('Failed to fetch freelancers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch freelancers.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(FreelancerProfile $freelancerProfile): JsonResponse
    {
        return response()->json($freelancerProfile);
    }

    public function store(FreelancerRequest $request): JsonResponse
    {
        try {
            $freelancerData = $this->freelancerProfileRepository->prepareFreelancerData($request);

            $freelancerProfile = $this->freelancerProfileRepository->createFreelancerProfile($freelancerData);

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

            $this->freelancerProfileRepository->updateFreelancerProfile($freelancerProfile, $freelancerData);

            return response()->json([
                'message' => trans('messages.freelancer_updated'),
                'freelancerProfile' => $freelancerProfile,
            ]);
        } catch (\Exception $e) {
            Log::error('Freelancer profile update failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.freelancer_update_failed')], Response::HTTP_BAD_REQUEST);
        }
    }
    public function getFreelancerProfile($id): JsonResponse
    {
        try {
            $freelancer = FreelancerProfile::with('user:id,email,first_name,last_name,phone')
                ->findOrFail($id);
            return response()->json($freelancer);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Freelancer not found'], 404);
        }
    }
    public function rate(FreelancerRequest $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $freelancerProfile = FreelancerProfile::findOrFail($id);

            if ($validatedData['reviews'] > $freelancerProfile->best_review) {
                $freelancerProfile->best_review = $validatedData['reviews'];
            }

            $freelancerProfile->total_reviews_count += 1;

            $freelancerProfile->save();

            return response()->json(['message' => 'Rating submitted successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to submit rating: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to submit rating.'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function hasRated(FreelancerRequest $request, $id)
    {
        $userId = $request->query('user_id');
        if (!$userId) {
            return response()->json(['error' => 'User ID is required.'], 400);
        }

        $freelancerProfile = FreelancerProfile::find($id);

        if (!$freelancerProfile) {
            return response()->json(['message' => 'Freelancer not found.'], 404);
        }

        $clientsRated = $freelancerProfile->clients_rated ? json_decode($freelancerProfile->clients_rated, true) : [];

        if (!is_array($clientsRated)) {
            Log::warning('clients_rated is not an array for freelancer profile ID: ' . $id);
            return response()->json(['error' => 'Invalid data format for clients_rated.'], 500);
        }

        $hasRated = in_array($userId, $clientsRated);

        return response()->json(['hasRated' => $hasRated]);
    }

    public function destroy(FreelancerProfile $freelancerProfile): JsonResponse
    {
        try {
            $this->freelancerProfileRepository->deleteFreelancerProfile($freelancerProfile);

            return response()->json([
                'message' => trans('messages.freelancer_deleted'),
            ], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            Log::error('Freelancer profile deletion failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.freelancer_deletion_failed')], Response::HTTP_BAD_REQUEST);
        }
    }
}
