<?php

namespace App\Repository;

use App\Models\FreelancerProfile;

class FreelancerProfileRepository
{
    public function prepareFreelancerData($request): array
    {
        return [
            'user_id' => $request->user_id,
            'bio' => $request->bio,
            'skills' => $request->skills,
            'hourly_price' => $request->hourly_price,
        ];
    }
    public function createFreelancerProfile(array $freelancerData): FreelancerProfile
    {
        return FreelancerProfile::create($freelancerData);
    }
    public function updateFreelancerProfile(FreelancerProfile $freelancerProfile, array $freelancerData): bool
    {
        return $freelancerProfile->update($freelancerData);
    }
    public function deleteFreelancerProfile(FreelancerProfile $freelancerProfile): bool
    {
        return $freelancerProfile->delete();
    }
}
