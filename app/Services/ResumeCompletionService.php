<?php

namespace App\Services;

use App\Models\Resume;

class ResumeCompletionService
{
    public static function calculate(Resume $resume): int
    {
        $score = 0;

        // Personal Info – 20%
        if (
            $resume->personalDetails &&
            $resume->personalDetails->full_name &&
            $resume->personalDetails->email &&
            $resume->personalDetails->designation
        ) {
            $score += 20;
        }

        // Summary – 10%
        if (!empty($resume->summary)) {
            $score += 10;
        }

        // Skills – 15%
        if ($resume->skills && count($resume->skills) >= 3) {
            $score += 15;
        }

        // Education – 15%
        if ($resume->education->contains(fn ($e) =>
            $e->institution && $e->degree
        )) {
            $score += 15;
        }

        // Experience – 20%
        if ($resume->experiences->contains(fn ($e) =>
            $e->organisation && $e->position
        )) {
            $score += 20;
        }

        // Projects – 10%
        if ($resume->projects->contains(fn ($p) => $p->name)) {
            $score += 10;
        }

        // Certifications – 5%
        if ($resume->certifications->contains(fn ($c) => $c->title)) {
            $score += 5;
        }

        // Social links – 5%
        if ($resume->socials && collect($resume->socials)->filter()->isNotEmpty()) {
            $score += 5;
        }

        return min($score, 100);
    }
}
