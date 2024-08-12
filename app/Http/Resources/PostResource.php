<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'title' => $this->title ?? null,
            'content' => $this->content ?? null,
            'categories' => [
                'id' => $this->categories->pluck('id') ?? null,
                'name' => $this->categories->pluck('name')  ?? null,
            ],
            'user' => $this->user->name ?? null,
            'comments' => $this->comments ?? [],
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
        ];
    }
}