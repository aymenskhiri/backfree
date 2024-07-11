<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{

    public function index(): Response
    {
        $posts = Post::all();
        return response($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $request->validate([

            'freelancer_profile_id' => 'required|exists:freelancer_profiles,id',
            'title' => 'required|string|max:' . config('constants.string_max'),
            'description' => 'required|string',
        ]);

        $post = Post::create($request->only(['freelancer_profile_id', 'title', 'description']));

        return response($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): Response
    {
        return response($post);
    }


    public function update(Request $request, Post $post): Response
    {
        $request->validate([
            'title' => 'required|string|max:' . config('constants.string_max'),
            'description' => 'required|string|max:' . config('constants.string_max'),

        ]);

        $post->update($request->only(['title', 'description']));

        return response($post);
    }


    public function destroy(Post $post): Response
    {
        $post->delete();

        return response()->noContent();
    }
}
