<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Post::class);
        $orderColumn = request('order_column', 'created_at');
        $orderDirection = request('order_direction', 'desc');

        if (!in_array($orderColumn, ['id', 'title', 'created_at'])) {
            $orderColumn = 'created_at';
        }

        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderColumn = 'desc';
        }

        $posts = Post::query()
            ->with('category:id,name')
            ->when(($user = $request->user()) != null, function ($query) use ($user) {
                if ($user->isAdmin())
                    return;
                return $query->where('user_id', $user->id);
            })
            ->when(request('search_category'), function ($query) {
                $query->where('category_id', request('search_category'));
            })
            ->when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
            })
            ->when(request('search_title'), function ($query) {
                $query->where('title', 'LIKE', '%' . request('search_title') . '%');
            })
            ->when(request('search_content'), function ($query) {
                $query->where('content', 'LIKE', '%' . request('search_content') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('title', 'like', '%' . request('search_global') . '%')
                        ->orWhere('content', 'like', '%' . request('search_global') . '%');
                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(10)
            ->appends([
                'category_id' => request('category_id'),
                'order_column' => $orderColumn,
                'order_direction' => $orderDirection,
                'category_id' => request('category_id')
            ]);

        return PostResource::collection($posts)->additional([
            'params' => [
                'order_column' => $orderColumn,
                'order_direction' => $orderDirection,
                'category_id' => request('category_id')
            ]
        ]);
    }

    public function store(PostStoreRequest $request)
    {
        $this->authorize('create', Post::class);
        $post = Post::create($request->validated() + [
            'user_id' => $request->user()->id
        ]);
        return response()->json((new PostResource($post)), 201);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return response()->json(new PostResource($post), 200);
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $post->update($request->validated());
        return response()->json(new PostResource($post->refresh()), 200);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return response()->noContent();
    }
}