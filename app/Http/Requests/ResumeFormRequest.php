<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumeFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add auth logic if needed
    }

    public function rules(): array
    {
        return [

           // CORE
        'title' => 'sometimes|required|string|min:5|max:255',
        'summary' => 'sometimes|required|string|min:10|max:1500',
        'skills' => 'sometimes|required|array|min:1',
        'skills.*' => 'string|min:2|max:50',

        'languages' => 'sometimes|required|array|min:1',
        'languages.*.name' => 'required|string|min:2',
        'languages.*.level' => 'required|string|in:basic,intermediate,fluent,native',

        'accent_color' => 'sometimes|required|string',
        'template' => 'sometimes|required|string',

        // PERSONAL
        'personal_details' => 'sometimes|required|array',
        'personal_details.full_name' => 'required|string|max:255',
        'personal_details.designation' => 'required|string|max:255',
        'personal_details.email' => 'required|email',
        'personal_details.phone' => 'nullable|string|max:20',
        'personal_details.location' => 'nullable|string|max:255',

        
       // PROJECTS
        'projects' => 'sometimes|nullable|array',

        'projects.*.name' => 'sometimes|nullable|string|max:255',
        'projects.*.description' => 'sometimes|nullable|string',

        'projects.*.tech_stack' => 'sometimes|nullable|array',
        'projects.*.tech_stack.*' => 'string|max:50',

        'projects.*.start_date' => 'sometimes|nullable|date',
        'projects.*.end_date' => 'sometimes|nullable|date|after_or_equal:projects.*.start_date',

        'projects.*.live_link' => 'sometimes|nullable|url',
        'projects.*.github_link' => 'sometimes|nullable|url',


        // EXPERIENCES
        'experiences' => 'sometimes|nullable|array',
        'experiences.*.organization' => 'required|string|max:255',
        'experiences.*.position' => 'required|string|max:255',
        'experiences.*.start_date' => 'required|date',
        'experiences.*.end_date' => 'nullable|date|after_or_equal:experiences.*.start_date',
        'experiences.*.description' => 'sometimes|nullable|string',
        'experiences.*.is_current' => 'boolean',

        // EDUCATION
        'education' => 'sometimes|nullable|array',
        'education.*.institution' => 'sometimes|nullable|string|max:255',
        'education.*.field' => 'sometimes|nullable|string|max:255',
        'education.*.degree' => 'sometimes|nullable|string|max:255',
        
        'education.*.grade' => 'sometimes|nullable|string|max:20',
        'education.*.end_date' => 'sometimes|nullable|date|after_or_equal:education.*.start_date',
        'education.*.start_date' => 'sometimes|nullable|date',


        // CERTIFICATIONS
        'certifications' => 'sometimes|nullable|array',
        'certifications.*.title' => 'required|string|max:255',
        'certifications.*.issuer' => 'required|string|max:255',
        'certifications.*.issued_date' => 'sometimes|nullable|date',
        'certifications.*.url' => 'sometimes|nullable|url',
        // SOCIALS
        'socials' => 'sometimes|nullable|array',
        'socials.linkedin' => 'nullable|url',
        'socials.github' => 'nullable|url',
        'socials.portfolio' => 'nullable|url',
        'socials.twitter' => 'nullable|url',
        ];
    }
}