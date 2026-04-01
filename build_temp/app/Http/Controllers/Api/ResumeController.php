<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\Template;
use App\Models\User;
use App\Services\TemplateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    private TemplateValidator $templateValidator;

    public function __construct()
    {
        $this->templateValidator = new TemplateValidator();
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $resumes = Resume::byUser($user->id)
            ->with('template')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($resumes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:templates,id',
            'personal_data' => 'required|array',
            'experiences' => 'nullable|array',
            'educations' => 'nullable|array',
            'skills' => 'nullable|array',
            'primary_color' => 'string|regex:/^#[0-9A-Fa-f]{6}$/',
            'font_family' => 'string',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $user = Auth::user();
            $template = Template::findOrFail($validated['template_id']);

            if ($user && !$user->canCreateMoreCVs()) {
                return response()->json([
                    'error' => 'CV limit reached. Upgrade to premium for unlimited CVs.'
                ], 403);
            }

            if ($template->is_premium && (!$user || !$user->isPremium())) {
                return response()->json([
                    'error' => 'This template is only available for premium users.'
                ], 403);
            }

            $resume = Resume::create([
                'user_id' => $user ? $user->id : null,
                'session_id' => $user ? null : session()->getId(),
                'template_id' => $validated['template_id'],
                'title' => $validated['personal_data']['name'] ?? 'Untitled CV',
                'personal_data' => $validated['personal_data'],
                'experiences' => $validated['experiences'] ?? [],
                'educations' => $validated['educations'] ?? [],
                'skills' => $validated['skills'] ?? [],
                'primary_color' => $validated['primary_color'] ?? '#3490dc',
                'font_family' => $validated['font_family'] ?? 'Poppins',
                'is_completed' => true,
            ]);

            return response()->json([
                'id' => $resume->id,
                'preview_url' => route('resume.preview', $resume->id),
                'message' => 'Resume created successfully'
            ], 201);
        });
    }

    public function show(string $id)
    {
        $resume = Resume::with('template')->findOrFail($id);

        $user = Auth::user();
        $sessionId = session()->getId();

        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$resume->user_id && $resume->session_id !== $sessionId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($resume);
    }

    public function update(Request $request, string $id)
    {
        $resume = Resume::findOrFail($id);

        $user = Auth::user();
        $sessionId = session()->getId();

        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$resume->user_id && $resume->session_id !== $sessionId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'personal_data' => 'sometimes|array',
            'experiences' => 'sometimes|array',
            'educations' => 'sometimes|array',
            'skills' => 'sometimes|array',
            'primary_color' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'font_family' => 'sometimes|string',
            'title' => 'sometimes|string|max:255',
        ]);

        $resume->update($validated);

        return response()->json([
            'success' => true,
            'preview_url' => route('resume.preview', $resume->id)
        ]);
    }

    public function generatePdf(Request $request, string $id)
    {
        $resume = Resume::with('template')->findOrFail($id);

        $user = Auth::user();
        $sessionId = session()->getId();

        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$resume->user_id && $resume->session_id !== $sessionId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate template using whitelist
        $templateView = $this->templateValidator->getSafeViewName($resume->template->slug);

        $pdf = Pdf::loadView($templateView, ['resume' => $resume])
                  ->setPaper('a4', 'portrait')
                  ->setWarnings(false);

        $filename = 'resume_' . $resume->id . '_' . time() . '.pdf';
        $path = 'public/pdfs/' . $filename;

        Storage::put($path, $pdf->output());

        $resume->incrementDownload();

        $downloadUrl = Storage::url($path);

        return response()->json([
            'download_url' => $downloadUrl,
            'expires_in' => 86400 // 24 hours
        ]);
    }

    public function atsScore(Request $request, string $id)
    {
        $resume = Resume::findOrFail($id);

        $user = Auth::user();
        $sessionId = session()->getId();

        if ($resume->user_id && (!$user || $resume->user_id !== $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$resume->user_id && $resume->session_id !== $sessionId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user && !$user->canUseATS()) {
            return response()->json([
                'error' => 'ATS scan limit reached. Upgrade to premium for unlimited scans.'
            ], 403);
        }

        $jobPosition = $request->input('job_position', 'Software Engineer');

        $score = $this->calculateATSScore($resume, $jobPosition);
        $feedback = $this->generateATSFeedback($resume, $jobPosition);

        $resume->update([
            'ats_score' => $score,
            'ats_feedback' => $feedback
        ]);

        if ($user && $user->subscription_status === 'free') {
            $user->decrement('free_ats_limit');
        }

        return response()->json([
            'score' => $score,
            'feedback' => $feedback,
            'suggestions' => $this->generateATSSuggestions($resume, $jobPosition)
        ]);
    }

    private function calculateATSScore($resume, $jobPosition)
    {
        $score = 0;
        $maxScore = 100;

        // Check required sections (30 points)
        if (!empty($resume->personal_data['name'])) $score += 5;
        if (!empty($resume->personal_data['email'])) $score += 5;
        if (!empty($resume->personal_data['phone'])) $score += 5;
        if (!empty($resume->experiences) && count($resume->experiences) > 0) $score += 5;
        if (!empty($resume->educations) && count($resume->educations) > 0) $score += 5;
        if (!empty($resume->skills) && count($resume->skills) >= 3) $score += 5;

        // Check date format consistency (20 points)
        $dateConsistent = $this->checkDateConsistency($resume);
        $score += $dateConsistent ? 20 : 0;

        // Check keyword density (25 points)
        $keywordScore = $this->checkKeywordDensity($resume, $jobPosition);
        $score += $keywordScore;

        // Check readability (15 points)
        $readabilityScore = $this->checkReadability($resume);
        $score += $readabilityScore;

        // Check file compatibility (10 points)
        $score += 10; // Always using PDF format

        return min($score, $maxScore);
    }

    private function checkDateConsistency($resume)
    {
        $datePattern = '/^(0[1-9]|1[0-2])\/\d{4}$/';

        // Check experiences dates
        if (!empty($resume->experiences)) {
            foreach ($resume->experiences as $exp) {
                if (isset($exp['start_date']) && !preg_match($datePattern, $exp['start_date'])) {
                    return false;
                }
                if (isset($exp['end_date']) && $exp['end_date'] !== 'Present' && !preg_match($datePattern, $exp['end_date'])) {
                    return false;
                }
            }
        }

        // Check education dates
        if (!empty($resume->educations)) {
            foreach ($resume->educations as $edu) {
                if (isset($edu['graduation_year']) && !preg_match('/^\d{4}$/', $edu['graduation_year'])) {
                    return false;
                }
            }
        }

        return true;
    }

    private function checkKeywordDensity($resume, $jobPosition)
    {
        $keywords = $this->getJobKeywords($jobPosition);
        $content = '';

        // Collect all text content
        if (!empty($resume->personal_data['summary'])) {
            $content .= ' ' . $resume->personal_data['summary'];
        }

        if (!empty($resume->experiences)) {
            foreach ($resume->experiences as $exp) {
                if (isset($exp['description'])) {
                    $content .= ' ' . $exp['description'];
                }
            }
        }

        if (!empty($resume->skills)) {
            $content .= ' ' . implode(' ', $resume->skills);
        }

        $content = strtolower($content);
        $foundKeywords = 0;

        foreach ($keywords as $keyword) {
            if (strpos($content, strtolower($keyword)) !== false) {
                $foundKeywords++;
            }
        }

        $percentage = ($foundKeywords / count($keywords)) * 100;
        return min(25, round($percentage / 4));
    }

    private function checkReadability($resume)
    {
        $score = 15; // Base score

        // Check if summary is not too long
        if (!empty($resume->personal_data['summary'])) {
            $summaryLength = strlen($resume->personal_data['summary']);
            if ($summaryLength > 500) {
                $score -= 5;
            }
        }

        // Check if skills are reasonable number
        if (!empty($resume->skills)) {
            $skillCount = count($resume->skills);
            if ($skillCount > 10) {
                $score -= 5;
            } elseif ($skillCount < 3) {
                $score -= 5;
            }
        }

        return max(0, $score);
    }

    private function getJobKeywords($jobPosition)
    {
        $keywords = [
            'Software Engineer' => ['javascript', 'python', 'java', 'react', 'node.js', 'git', 'sql', 'api', 'database', 'frontend', 'backend'],
            'Product Manager' => ['product', 'strategy', 'roadmap', 'agile', 'scrum', 'analytics', 'user experience', 'stakeholder', 'metrics'],
            'Data Scientist' => ['python', 'r', 'machine learning', 'statistics', 'data analysis', 'sql', 'tableau', 'visualization'],
            'Marketing Manager' => ['marketing', 'strategy', 'campaign', 'social media', 'content', 'seo', 'analytics', 'brand'],
        ];

        return $keywords[$jobPosition] ?? $keywords['Software Engineer'];
    }

    private function generateATSFeedback($resume, $jobPosition)
    {
        $feedback = [];

        // Check missing sections
        if (empty($resume->personal_data['email'])) {
            $feedback[] = 'Email address is missing';
        }

        if (empty($resume->personal_data['phone'])) {
            $feedback[] = 'Phone number is missing';
        }

        if (empty($resume->experiences)) {
            $feedback[] = 'Work experience section is empty';
        }

        if (empty($resume->educations)) {
            $feedback[] = 'Education section is empty';
        }

        if (empty($resume->skills) || count($resume->skills) < 3) {
            $feedback[] = 'Add at least 3 skills to your resume';
        }

        // Check date consistency
        if (!$this->checkDateConsistency($resume)) {
            $feedback[] = 'Date format should be consistent (MM/YYYY)';
        }

        return $feedback;
    }

    private function generateATSSuggestions($resume, $jobPosition)
    {
        $suggestions = [];
        $keywords = $this->getJobKeywords($jobPosition);

        $content = '';
        if (!empty($resume->skills)) {
            $content .= ' ' . implode(' ', $resume->skills);
        }

        $missingKeywords = [];
        foreach ($keywords as $keyword) {
            if (strpos(strtolower($content), strtolower($keyword)) === false) {
                $missingKeywords[] = $keyword;
            }
        }

        if (!empty($missingKeywords)) {
            $suggestions[] = 'Consider adding these relevant keywords: ' . implode(', ', array_slice($missingKeywords, 0, 5));
        }

        if (strlen($resume->personal_data['summary'] ?? '') > 500) {
            $suggestions[] = 'Keep your professional summary under 500 characters for better ATS readability';
        }

        if (count($resume->skills ?? []) > 10) {
            $suggestions[] = 'Consider reducing skills to 10 or fewer for better readability';
        }

        return $suggestions;
    }
}
