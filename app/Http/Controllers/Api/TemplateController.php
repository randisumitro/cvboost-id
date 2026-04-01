<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $templates = Template::active()->ordered();

        // If user is not premium, only return free templates
        if (!$user || !$user->isPremium()) {
            $templates = $templates->free();
        }

        $templates = $templates->get();

        return response()->json($templates);
    }

    public function show(string $id)
    {
        $template = Template::active()->findOrFail($id);

        $user = Auth::user();

        if ($template->is_premium && (!$user || !$user->isPremium())) {
            return response()->json([
                'error' => 'This template is only available for premium users.'
            ], 403);
        }

        return response()->json($template);
    }

    public function preview(string $id)
    {
        $template = Template::active()->findOrFail($id);

        $user = Auth::user();

        if ($template->is_premium && (!$user || !$user->isPremium())) {
            return response()->json([
                'error' => 'This template is only available for premium users.'
            ], 403);
        }

        // Create a mock resume with sample data for preview
        $sampleResume = new \App\Models\Resume([
            'title' => 'Sample Resume',
            'personal_data' => [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+62 812-3456-7890',
                'address' => 'Jakarta, Indonesia',
                'linkedin' => 'linkedin.com/in/johndoe',
                'portfolio' => 'johndoe.dev',
                'summary' => 'Experienced Software Engineer with 5+ years of expertise in web development and system design.'
            ],
            'experiences' => [
                [
                    'position' => 'Senior Software Engineer',
                    'company' => 'Tech Company',
                    'location' => 'Jakarta',
                    'start_date' => '01/2020',
                    'end_date' => 'Present',
                    'description' => 'Led development of scalable web applications and mentored junior developers.'
                ],
                [
                    'position' => 'Software Engineer',
                    'company' => 'Startup Inc',
                    'location' => 'Bandung',
                    'start_date' => '06/2018',
                    'end_date' => '12/2019',
                    'description' => 'Developed RESTful APIs and implemented frontend features using React.'
                ]
            ],
            'educations' => [
                [
                    'degree' => 'Bachelor of Computer Science',
                    'institution' => 'University of Indonesia',
                    'graduation_year' => '2018',
                    'gpa' => '3.8'
                ]
            ],
            'skills' => ['JavaScript', 'PHP', 'Laravel', 'React', 'Node.js', 'MySQL', 'Git', 'Docker'],
            'primary_color' => '#3490dc',
            'font_family' => 'Poppins',
            'template_id' => $template->id
        ]);
        $sampleResume->setRelation('template', $template);

        // Use the Blade template directly
        $viewName = 'resume.templates.' . $template->slug;
        if (!view()->exists($viewName)) {
            $viewName = 'resume.templates.modern-professional';
        }

        $html = view($viewName, ['resume' => $sampleResume])->render();

        return response()->json([
            'html' => $html,
            'css' => $template->css_content
        ]);
    }
}
