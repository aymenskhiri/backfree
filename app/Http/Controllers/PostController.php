<?php

namespace App\Http\Controllers;

use App\Http\Requests\FreelancerRequest;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request): JsonResponse
    {
        try {
            $post = Post::create($request->only(['freelancer_profile_id', 'title', 'description']));
            return response()->json([
                'message' => trans('messages.post_created'),
                'post' => $post,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Post creation failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.post_creation_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json($post);
    }

    public function update(FreelancerRequest $request, Post $post): JsonResponse
    {

        try {
            $post->update($request->only(['title', 'description']));
            return response()->json([
                'message' => trans('messages.post_updated'),
                'post' => $post,
            ]);
        } catch (\Exception $e) {
            Log::error('Post update failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.post_update_failed')], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Post $post): JsonResponse
    {
        try {
            $post->delete();
            return response()->json([
                'message' => trans('messages.post_deleted'),
            ], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            Log::error('Post deletion failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.post_deletion_failed')], Response::HTTP_BAD_REQUEST);
        }
    }
}
