<?php

namespace App\Repository;

class PostRepository
{
    public function preparePostData($request, $imagePath): array
    {
        return [
            'freelancer_profile_id' => $request->input('freelancer_profile_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ];
    }
}
