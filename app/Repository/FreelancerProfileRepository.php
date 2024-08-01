<?php

namespace App\Repository;

class FreelancerProfileRepository
{
    public function prepareFreelancerData($request): array
    {
        return [
            'user_id' => $request->user_id,
            'bio' => $request->bio,
            'skills' => $request->skills,
            'hourly_price' => $request->hourly_price,
            'reviews' => $request->reviews ?? 0,
        ];
    }
}
