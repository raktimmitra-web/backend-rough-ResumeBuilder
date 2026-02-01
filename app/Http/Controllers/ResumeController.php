<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResumeFormRequest;
use App\Http\Requests\StoreResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    /* ======================
        LIST RESUMES
    ====================== */
    public function index(Request $request)
    {
        $resumes = $request->user()
            ->resumes()
            ->latest('updated_at')
            ->get(['id', 'title', 'template', 'updated_at']);

        return response()->json([
            'success' => true,
            'data' => $resumes
        ]);
    }

    /* ======================
        SHOW RESUME
    ====================== */
    public function show(Request $request, string $id)
    {
        $resume = $request->user()
            ->resumes()
            ->with([
                'personalDetails',
                'projects',
                'experiences',
                'education',
                'certifications',
                'socials',
            ])
            ->findOrFail($id);

        return new ResumeResource($resume);
    }

    /* ======================
        CREATE RESUME
    ====================== */
    public function store(StoreResumeRequest $request)
    {
        $resume = $request->user()->resumes()->create([
        'title' => $request->title,

        // optional defaults (recommended)
        'template' => 'template-1',
        'accent_color' => '#3B82F6',
        'summary' => '',
        'skills' => [],
        'languages' => [],
    ]);

    return new ResumeResource($resume);
    }

    /* ======================
        UPDATE RESUME
    ====================== */
    public function update(ResumeFormRequest $request, string $id)
{
    $resume = $request->user()
        ->resumes()
        ->findOrFail($id);

    $data = $request->validated();

    DB::transaction(function () use ($resume, $data) {

        /* ======================
           CORE FIELDS (ONLY IF SENT)
        ====================== */
        $coreFields = [];

        if (array_key_exists('title', $data)) {
            $coreFields['title'] = $data['title'];
        }

        if (array_key_exists('summary', $data)) {
            $coreFields['summary'] = $data['summary'];
        }

        if (array_key_exists('skills', $data)) {
            $coreFields['skills'] = $data['skills'];
        }

        if (array_key_exists('languages', $data)) {
            $coreFields['languages'] = $data['languages'];
        }

        if (array_key_exists('accent_color', $data)) {
            $coreFields['accent_color'] = $data['accent_color'];
        }

        if (array_key_exists('template', $data)) {
            $coreFields['template'] = $data['template'];
        }

        if (!empty($coreFields)) {
            $resume->update($coreFields);
        }

        /* ======================
           PERSONAL DETAILS (hasOne)
        ====================== */
        if (array_key_exists('personal_details', $data)) {
            $resume->personalDetails()->updateOrCreate(
                [],
                $data['personal_details'] ?? []
            );
        }

        /* ======================
           SOCIALS (hasOne)
        ====================== */
        if (array_key_exists('socials', $data)) {
            $resume->socials()->updateOrCreate(
                [],
                $data['socials'] ?? []
            );
        }

        /* ======================
           HAS MANY SECTIONS
           ✅ Only sync if that section is sent
        ====================== */
        if (array_key_exists('projects', $data)) {
            $this->syncHasMany($resume, 'projects', $data['projects'] ?? []);
        }

        if (array_key_exists('experiences', $data)) {
            $this->syncHasMany($resume, 'experiences', $data['experiences'] ?? []);
        }

        if (array_key_exists('education', $data)) {
            $this->syncHasMany($resume, 'education', $data['education'] ?? []);
        }

        if (array_key_exists('certifications', $data)) {
            $this->syncHasMany($resume, 'certifications', $data['certifications'] ?? []);
        }
    });

    return response()->json([
        'success' => true,
        'message' => 'Resume updated successfully',
    ]);
}


    /* ======================
        DELETE RESUME
    ====================== */
    public function destroy(Request $request, string $id)
    {
        $resume = $request->user()
            ->resumes()
            ->findOrFail($id);

        $resume->delete();

        return response()->json(['success' => true]);
    }

    /* ======================
        SAFE HAS-MANY SYNC
    ====================== */
    protected function syncHasMany($resume, string $relation, array $items): void
    {
        if (!is_array($items)) {
            return;
        }

        $resume->$relation()->delete();

        if (count($items) === 0) {
            return;
        }

        $resume->$relation()->createMany(
            collect($items)
                ->filter(fn($item) => is_array($item))
                ->values()
                ->toArray()
        );
    }
}