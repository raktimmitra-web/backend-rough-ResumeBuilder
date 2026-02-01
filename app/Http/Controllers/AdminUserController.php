<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
     public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->when($request->role, fn($q)=>$q->where('role',$request->role))
            ->when($request->status, fn($q)=>$q->where('status',$request->status))
            ->latest()
            ->paginate(10);

        return UserResource::collection($users);
    }
    
    public function show($id){
        $user = User::withCount('resumes')->findOrFail($id);
        return response()->json([
        'id'            => $user->id,
        'name'          => $user->name,
        'email'         => $user->email,
        'role'          => $user->role,
        'status'        => $user->status,
        'is_premium'    => (bool) $user->is_premium,
        'joined_at'     => $user->created_at?->format('M d, Y'),
        'last_login'    => $user->last_login_at
                            ? $user->last_login_at->diffForHumans()
                            : null,
        'resume_count'  => $user->resumes_count,
    ]);
    }
  

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'   => 'sometimes|string',
            'role'   => 'sometimes|in:user,admin',
            'status' => 'sometimes|in:active,suspended',
        ]);

        $user->update($data);

        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return response()->json([
                'message' => 'Cannot delete admin user'
            ], 403);
        }

        $user->delete();

        return response()->noContent();
    }

    public function bulkSuspend(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        User::whereIn('id', $request->ids)
            ->where('role', 'user')
            ->update(['status' => 'suspended']);

        return response()->json(['success' => true]);
    }
}
