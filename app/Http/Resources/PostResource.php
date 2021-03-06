<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => CategoryController::getAllPostCategories($this->id),
            'rating' => PostController::getPostRating($this->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
