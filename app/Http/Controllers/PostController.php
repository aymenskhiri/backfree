<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\FreelancerProfile;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['freelancerProfile.user'])->get();
        return response()->json($posts);
    }

    public function store(PostRequest $request): JsonResponse
    {
        try {
            // Handle the image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/images');
                $imagePath = basename($imagePath); // Get only the filename
            }

            $post = Post::create([
                'freelancer_profile_id' => $request->input('freelancer_profile_id'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $imagePath,
            ]);

            $post->load('freelancer_profile');

            return response()->json([
                'message' => trans('messages.post_created'),
                'post' => $post,
            ],Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('messages.post_creation_failed'),
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getPostsByFreelancer($freelancerId): JsonResponse
    {
        try {
            $posts = Post::where('freelancer_profile_id', $freelancerId)->get();
            return response()->json($posts);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve posts for freelancer: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve posts'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(PostRequest $request, $id): JsonResponse
    {
        try {
            $post = Post::findOrFail($id);

            // Handle the image upload
            $imagePath = $post->image;
            if ($request->hasFile('image')) {
                if ($imagePath) {
                    Storage::delete('public/images/' . $imagePath);
                }
                $imagePath = $request->file('image')->store('public/images');
                $imagePath = basename($imagePath);
            }

            // Update the post
            $post->update([
                'freelancer_profile_id' => $request->input('freelancer_profile_id'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $imagePath,
            ]);

            $post->load('freelancer_profile');

            return response()->json([
                'message' => 'Post updated successfully',
                'post' => $post,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Post update failed',
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $post = Post::findOrFail($id);

            // Delete the image if it exists
            if ($post->image) {
                Storage::delete('public/images/' . $post->image);
            }

            $post->delete();

            return response()->json([
                'message' => 'Post deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Post deletion failed',
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
