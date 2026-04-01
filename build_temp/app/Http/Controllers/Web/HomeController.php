<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredTemplates = Template::active()->take(6)->get();

        return view('home', compact('featuredTemplates'));
    }

    public function pricing()
    {
        return view('pricing');
    }

    public function blog()
    {
        $posts = [
            [
                'title' => '10 Tips for Creating a Resume That Passes ATS',
                'slug' => 'tips-resume-pass-ats',
                'excerpt' => 'Learn how to optimize your resume to get through Applicant Tracking Systems and increase your chances of getting hired.',
                'date' => '2024-03-15',
                'author' => 'CVBoost Team',
                'image' => 'blog/ats-tips.jpg'
            ],
            [
                'title' => 'How to Write a Professional Summary That Stands Out',
                'slug' => 'write-professional-summary',
                'excerpt' => 'Your professional summary is the first thing recruiters read. Make it count with these expert tips.',
                'date' => '2024-03-10',
                'author' => 'CVBoost Team',
                'image' => 'blog/professional-summary.jpg'
            ],
            [
                'title' => 'The Ultimate Guide to Resume Formatting',
                'slug' => 'resume-formatting-guide',
                'excerpt' => 'Proper formatting can make or break your resume. Learn the best practices for 2024.',
                'date' => '2024-03-05',
                'author' => 'CVBoost Team',
                'image' => 'blog/resume-formatting.jpg'
            ],
            [
                'title' => 'Common Resume Mistakes to Avoid',
                'slug' => 'common-resume-mistakes',
                'excerpt' => 'Avoid these common resume mistakes that could cost you your dream job.',
                'date' => '2024-02-28',
                'author' => 'CVBoost Team',
                'image' => 'blog/resume-mistakes.jpg'
            ],
            [
                'title' => 'How to Tailor Your Resume for Each Job Application',
                'slug' => 'tailor-resume-job-application',
                'excerpt' => 'Learn why tailoring your resume for each application is crucial and how to do it efficiently.',
                'date' => '2024-02-20',
                'author' => 'CVBoost Team',
                'image' => 'blog/tailor-resume.jpg'
            ]
        ];

        return view('blog.index', compact('posts'));
    }

    public function blogPost($slug)
    {
        $posts = [
            'tips-resume-pass-ats' => [
                'title' => '10 Tips for Creating a Resume That Passes ATS',
                'content' => '<p>Applicant Tracking Systems (ATS) are software used by employers to filter job applications. Here are 10 tips to ensure your resume passes ATS:</p>
                    <ol>
                        <li><strong>Use standard fonts:</strong> Arial, Calibri, Georgia, Helvetica, Times New Roman</li>
                        <li><strong>Avoid graphics and tables:</strong> ATS can\'t read images or complex tables</li>
                        <li><strong>Use standard section headings:</strong> Work Experience, Education, Skills</li>
                        <li><strong>Include keywords from job description:</strong> Match the language used in the posting</li>
                        <li><strong>Save as .docx or .pdf:</strong> These formats are ATS-friendly</li>
                        <li><strong>Use bullet points:</strong> Easy for ATS to parse</li>
                        <li><strong>Include contact information:</strong> Name, email, phone, LinkedIn</li>
                        <li><strong>Avoid fancy formatting:</strong> No columns, boxes, or headers/footers</li>
                        <li><strong>Use reverse chronological order:</strong> Most recent experience first</li>
                        <li><strong>Proofread carefully:</strong> Typos can confuse ATS algorithms</li>
                    </ol>',
                'date' => '2024-03-15',
                'author' => 'CVBoost Team'
            ],
            'write-professional-summary' => [
                'title' => 'How to Write a Professional Summary That Stands Out',
                'content' => '<p>Your professional summary is your elevator pitch to recruiters. Here\'s how to make it compelling:</p>
                    <h3>What is a Professional Summary?</h3>
                    <p>A professional summary is a 2-4 sentence overview of your qualifications, experience, and career goals. It appears at the top of your resume, right after your contact information.</p>
                    <h3>Key Elements to Include:</h3>
                    <ul>
                        <li>Your job title and years of experience</li>
                        <li>Key skills and areas of expertise</li>
                        <li>Notable achievements or accomplishments</li>
                        <li>Career goals (optional)</li>
                    </ul>
                    <h3>Example:</h3>
                    <p>"Experienced Software Engineer with 5+ years of expertise in full-stack development, specializing in React and Node.js. Proven track record of delivering scalable web applications that improve user engagement by 30%. Seeking to leverage technical skills and leadership experience in a senior engineering role."</p>',
                'date' => '2024-03-10',
                'author' => 'CVBoost Team'
            ]
        ];

        $post = $posts[$slug] ?? null;

        if (!$post) {
            abort(404);
        }

        return view('blog.post', compact('post'));
    }
}
