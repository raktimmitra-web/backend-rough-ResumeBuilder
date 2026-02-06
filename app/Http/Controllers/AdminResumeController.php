<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminResumeController extends Controller
{
      public function index(Request $request)
    {
        $resumes = Resume::with('user:id,name,email') // ✅ ADD THIS
            ->when($request->search, function ($q) use ($request) {

                $q->where(function ($sub) use ($request) {

                    $sub->where('title', 'like', "%{$request->search}%")
                        ->orWhereHas('user', function ($u) use ($request) {
                            $u->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%");
                        });

                });

            })
            ->when($request->template, fn ($q) =>
                $q->where('template', $request->template)
            )
            ->orderByDesc('updated_at') // better than latest()
            ->paginate(10);

        return response()->json([
            'data' => $resumes->items(),
            'meta' => [
                'current_page' => $resumes->currentPage(),
                'last_page' => $resumes->lastPage(),
                'total' => $resumes->total(),
            ]
        ]);
    }


    /**
     * View single resume (optional but useful)
     */
   public function show($id)
    {
        $resume = Resume::with([
            'user:id,name,email',
            'personalDetails',
            'projects',
            'experiences',
            'education',
            'certifications',
            'socials',
        ])->findOrFail($id);

        return response()->json($resume);
    }


    /**
     * Delete a resume safely
     */
    public function destroy($id)
    {
        $resume = Resume::findOrFail($id);

        $resume->delete();

        return response()->json([
            'message' => 'Resume deleted successfully',
        ]);
    }
}
