<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class AdminResumeController extends Controller
{
       public function index(Request $request)
    {
        $resumes = Resume::with('user:id,name,email')
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function ($u) use ($request) {
                      $u->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                  });
            })
            ->when($request->template, fn ($q) =>
                $q->where('template', $request->template)
            )
            ->latest()
            ->paginate(10);

        return response()->json($resumes);
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
            'skills',
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

        // Important: delete children first if cascade is not set
        $resume->personalDetails()?->delete();
        $resume->projects()->delete();
        $resume->experiences()->delete();
        $resume->education()->delete();
        $resume->certifications()->delete();
        $resume->skills()->delete();
        $resume->socials()?->delete();

        $resume->delete();

        return response()->json([
            'message' => 'Resume deleted successfully',
        ]);
    }
}
