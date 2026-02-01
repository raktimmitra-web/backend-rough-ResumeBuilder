<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'role'        => $this->role,
            'is_premium'  => (bool) $this->is_premium,
            'status'      => $this->status,
            'joined_at'   => $this->created_at?->format('M d, Y'),
            'last_login'  => $this->last_login_at
                                ? $this->last_login_at->diffForHumans()
                                : null,
        ];
    }

}
