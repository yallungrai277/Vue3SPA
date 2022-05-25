<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_content' => substr($this->content, 0, 50) . '...',
            'content' => $this->content,
            'created_at' => $this->created_at->format('Y-m-d'),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', (new CategoryResource($this->category)))
        ];
    }
}
