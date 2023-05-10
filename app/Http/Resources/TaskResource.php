<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id'           => $this->id,
            'parent_id'    => $this->parent_id,
            'title'        => $this->title,
            'description'  => $this->description,
            'status'       => $this->status,
            'priority'     => $this->priority,
            'completed_at' => $this->completed_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'subtasks'     => self::collection($this->whenLoaded('subtasks'))
        ];
    }
}
