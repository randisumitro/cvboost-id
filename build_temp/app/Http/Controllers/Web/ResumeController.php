<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\Template;
use App\Services\CssSanitizer;
use App\Services\TemplateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    private TemplateValidator $templateValidator;
    private CssSanitizer $cssSanitizer;

    public function __construct()
    {
        $this->templateValidator = new TemplateValidator();
        $this->cssSanitizer = new CssSanitizer();
    }

    public function create()
    {
        $templates = Template::active()->ordered()->get();

        // Get current step from session or default to 1
        $currentStep = session('resume_builder.step', 1);
        $resumeData = session('resume_builder.data', []);

        return view('resume.create', compact('templates', 'currentStep', 'resumeData'));
    }

    public function storeStep(Request $request)
    {
        $step = $request->input('step');
        $data = session('resume_builder.data', []);

        switch ($step) {
            case 1:
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'nullable|string|max:20',
                    'address' => 'nullable|string|max:500',
                    'linkedin' => 'nullable|url|max:255',
                    'portfolio' => 'nullable|url|max:255',
                ]);

                $data['personal_data'] = $validated;
                break;

            case 2:
                $validated = $request->validate([
                    'summary' => 'nullable|string|max:500',
                ]);

                $data['personal_data']['summary'] = $validated['summary'];
                break;

            case 3:
                $validated = $request->validate([
                    'experiences' => 'nullable|array',
                    'experiences.*.position' => 'required|string|max:255',
                    'experiences.*.company' => 'required|string|max:255',
                    'experiences.*.location' => 'nullable|string|max:255',
                    'experiences.*.start_date' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{4}$/'],
                    'experiences.*.end_date' => ['nullable', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{4}$|^Present$/'],
                    'experiences.*.description' => 'nullable|string|max:1000',
                ]);

                $data['experiences'] = $validated['experiences'] ?? [];
                break;

            case 4:
                $validated = $request->validate([
                    'educations' => 'nullable|array',
                    'educations.*.degree' => 'required|string|max:255',
                    'educations.*.institution' => 'required|string|max:255',
                    'educations.*.graduation_year' => ['required', 'string', 'regex:/^\d{4}$/'],
                    'educations.*.gpa' => 'nullable|string|max:10',
                ]);

                $data['educations'] = $validated['educations'] ?? [];
                break;

            case 5:
                $validated = $request->validate([
                    'skills' => 'required|array|min:3|max:10',
                    'skills.*' => 'required|string|max:50',
                ]);

                $data['skills'] = $validated['skills'];
                break;

            case 6:
                $validated = $request->validate([
                    'template_id' => 'required|exists:templates,id',
                    'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                    'font_family' => 'required|string|in:Poppins,Arial,Helvetica,Times New Roman',
                ]);

                $data['template_id'] = $validated['template_id'];
                $data['primary_color'] = $validated['primary_color'];
                $data['font_family'] = $validated['font_family'];

                // Create the resume
                return $this->createResume($data);
        }

        // Save data to session and move to next step
        session(['resume_builder.data' => $data]);
        session(['resume_builder.step' => $step + 1]);

        return redirect()->route('resume.create');
    }

    public function previousStep(Request $request)
    {
        $currentStep = session('resume_builder.step', 1);
        $previousStep = max(1, $currentStep - 1);

        session(['resume_builder.step' => $previousStep]);

        return redirect()->route('resume.create');
    }

    public function reset()
    {
        session()->forget('resume_builder');
        return redirect()->route('resume.create');
    }

    private function createResume($data)
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();
            $template = Template::findOrFail($data['template_id']);

            if ($user && !$user->canCreateMoreCVs()) {
                return redirect()->back()
                    ->with('error', 'CV limit reached. Upgrade to premium for unlimited CVs.');
            }

            if ($template->is_premium && (!$user || !$user->isPremium())) {
                return redirect()->back()
                    ->with('error', 'This template is only available for premium users.');
            }

            $resume = Resume::create([
                'user_id' => $user ? $user->id : null,
                'session_id' => $user ? null : session()->getId(),
                'template_id' => $data['template_id'],
                'title' => $data['personal_data']['name'] ?? 'Untitled CV',
                'personal_data' => $data['personal_data'],
                'experiences' => $data['experiences'] ?? [],
                'educations' => $data['educations'] ?? [],
                'skills' => $data['skills'] ?? [],
                'primary_color' => $data['primary_color'],
                'font_family' => $data['font_family'],
                'is_completed' => true,
            ]);

            // Clear session data
            session()->forget('resume_builder');

            return redirect()->route('resume.preview', $resume->id)
                ->with('success', 'Resume created successfully!');
        });
    }

    public function preview($id)
    {
        $resume = Resume::with('template')->findOrFail($id);

        // Check permissions
        $user = Auth::user();
        $sessionId = session()->getId();

        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) {
            abort(403);
        }

        if (!$resume->user_id && $resume->session_id !== $sessionId) {
            abort(403);
        }

        return view('resume.preview', compact('resume'));
    }

    public function atsScorePage($id)
    {
        $resume = Resume::findOrFail($id);

        // Check permissions
        $user = Auth::user();
        $sessionId = session()->getId();

        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) {
            abort(403);
        }

        if (!$resume->user_id && $resume->session_id !== $sessionId) {
            abort(403);
        }

        // Dummy ATS logic to calculate a fallback score instead of 500ing
        $score = 0;
        if (!empty($resume->personal_data['name'])) $score += 20;
        if (!empty($resume->personal_data['summary'])) $score += 10;
        if (!empty($resume->experiences)) $score += min(30, count($resume->experiences) * 15);
        if (!empty($resume->educations)) $score += min(20, count($resume->educations) * 10);
        if (!empty($resume->skills)) $score += min(20, count($resume->skills) * 4);

        $resume->ats_score = $score;
        $resume->save();

        return view('resume.ats-score', compact('resume'));
    }

    public function htmlExport($id)
    {
        $resume = Resume::with('template')->findOrFail($id);

        // Check permissions
        $user = Auth::user();
        $sessionId = session()->getId();
        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) abort(403);
        if (!$resume->user_id && $resume->session_id !== $sessionId) abort(403);

        // Validate template using whitelist
        $viewName = $this->templateValidator->getSafeViewName($resume->template->slug);

        $htmlContent = view($viewName, compact('resume'))->render();
        $fullHtml = "<!DOCTYPE html><html><head><meta charset='utf-8'><title>" . e($resume->title) . "</title></head><body>{$htmlContent}</body></html>";

        $resume->incrementDownload();

        $safeFilename = preg_replace('/[^a-z0-9_-]/i', '_', $resume->full_name) . '_resume.html';

        return response()->streamDownload(function() use ($fullHtml) {
            echo $fullHtml;
        }, $safeFilename, ['Content-Type' => 'text/html']);
    }

    public function downloadPage($id)
    {
        $resume = Resume::with('template')->findOrFail($id);

        // Check permissions
        $user = Auth::user();
        $sessionId = session()->getId();

        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) {
            abort(403);
        }

        if (!$resume->user_id && $resume->session_id !== $sessionId) {
            abort(403);
        }

        $resume->incrementDownload();

        // Validate template using whitelist
        $viewName = $this->templateValidator->getSafeViewName($resume->template->slug);

        $pdf = Pdf::loadView($viewName, ['resume' => $resume])
                  ->setPaper('a4', 'portrait')
                  ->setWarnings(false);

        $safeFilename = preg_replace('/[^a-z0-9_-]/i', '_', $resume->full_name) . '_resume.pdf';

        return $pdf->download($safeFilename);
    }

    public function index()
    {
        $user = Auth::user();
        $resumes = $user->resumes()->with('template')->orderBy('updated_at', 'desc')->get();

        return view('resume.index', compact('resumes'));
    }

    public function switchTemplate(Request $request, $id)
    {
        $resume = Resume::findOrFail($id);

        // Check permissions
        $user = Auth::user();
        $sessionId = session()->getId();
        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) abort(403);
        if (!$resume->user_id && $resume->session_id !== $sessionId) abort(403);

        $validated = $request->validate([
            'template_id' => 'required|exists:templates,id'
        ]);

        $template = Template::findOrFail($validated['template_id']);
        if ($template->is_premium && (!$user || !$user->isPremium())) {
            return redirect()->back()->with('error', 'Premium template restricted.');
        }

        $resume->template_id = $template->id;
        $resume->save();

        return redirect()->route('resume.preview', $resume->id)->with('success', 'Template switched successfully!');
    }

    public function edit($id)
    {
        $resume = Resume::with('template')->findOrFail($id);

        // Check permissions - require authentication
        $user = Auth::user();
        if (!$user || $resume->user_id !== $user->id) {
            abort(403);
        }

        // For now, redirect to preview since edit form is not fully implemented
        return redirect()->route('resume.preview', $resume->id)
            ->with('info', 'Edit functionality coming soon. You can duplicate this resume and modify it during creation.');
    }

    public function duplicate(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $resume = Resume::findOrFail($id);

            // Check permissions
            $user = Auth::user();
            if (!$user || $resume->user_id !== $user->id) {
                abort(403);
            }

            if (!$user->canCreateMoreCVs()) {
                return redirect()->back()
                    ->with('error', 'CV limit reached. Upgrade to premium for unlimited CVs.');
            }

            $newResume = $resume->replicate();
            $newResume->title = $resume->title . ' (Copy)';
            $newResume->download_count = 0;
            $newResume->last_downloaded_at = null;
            $newResume->ats_score = null;
            $newResume->ats_feedback = null;
            $newResume->save();

            return redirect()->route('resume.preview', $newResume->id)
                ->with('success', 'Resume duplicated successfully!');
        });
    }

    public function destroy($id)
    {
        $resume = Resume::findOrFail($id);

        // Check permissions
        $user = Auth::user();
        if (!$user || $resume->user_id !== $user->id) {
            abort(403);
        }

        $resume->delete();

        return redirect()->route('resume.index')
            ->with('success', 'Resume deleted successfully!');
    }
}
