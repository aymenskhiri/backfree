<?php

namespace App\Http\Controllers;

use App\Models\FreelancerProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FreelancerProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FreelancerProfile  $freelancerProfile
     * @return \Illuminate\Http\Response
     */
    public function show(FreelancerProfile $freelancerProfile): Response
    {
        return response($freelancerProfile);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bio' => 'nullable|string',
            'competences' => 'nullable|string',
            'tarif_horaires' => 'nullable|numeric',
            'reviews' => 'nullable|integer',
        ]);

        $freelancerProfile = FreelancerProfile::create([
            'user_id' => $request->user_id,
            'bio' => $request->bio,
            'competences' => $request->competences,
            'tarif_horaires' => $request->tarif_horaires,
            'reviews' => $request->reviews ?? 0,
        ]);

        return response($freelancerProfile, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FreelancerProfile  $freelancerProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FreelancerProfile $freelancerProfile): Response
    {
        $request->validate([
            'bio' => 'nullable|string',
            'competences' => 'nullable|string',
            'tarif_horaires' => 'nullable|numeric',
            'reviews' => 'nullable|integer',
        ]);

        $freelancerProfile->update($request->only(['bio', 'competences', 'tarif_horaires', 'reviews']));

        return response($freelancerProfile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FreelancerProfile  $freelancerProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreelancerProfile $freelancerProfile): Response
    {
        $freelancerProfile->delete();

        return response()->noContent();
    }
}
