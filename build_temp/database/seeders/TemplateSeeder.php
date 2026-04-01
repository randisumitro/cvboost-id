<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Modern Professional',
                'slug' => 'modern-professional',
                'thumbnail' => 'templates/modern-professional-thumb.jpg',
                'preview_image' => 'templates/modern-professional-preview.jpg',
                'html_structure' => $this->getModernProfessionalHTML(),
                'css_content' => $this->getModernProfessionalCSS(),
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Clean Minimal',
                'slug' => 'clean-minimal',
                'thumbnail' => 'templates/clean-minimal-thumb.jpg',
                'preview_image' => 'templates/clean-minimal-preview.jpg',
                'html_structure' => $this->getCleanMinimalHTML(),
                'css_content' => $this->getCleanMinimalCSS(),
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Creative Designer',
                'slug' => 'creative-designer',
                'thumbnail' => 'templates/creative-designer-thumb.jpg',
                'preview_image' => 'templates/creative-designer-preview.jpg',
                'html_structure' => $this->getCreativeDesignerHTML(),
                'css_content' => $this->getCreativeDesignerCSS(),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Executive Classic',
                'slug' => 'executive-classic',
                'thumbnail' => 'templates/executive-classic-thumb.jpg',
                'preview_image' => 'templates/executive-classic-preview.jpg',
                'html_structure' => $this->getExecutiveClassicHTML(),
                'css_content' => $this->getExecutiveClassicCSS(),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Tech Developer',
                'slug' => 'tech-developer',
                'thumbnail' => 'templates/tech-developer-thumb.jpg',
                'preview_image' => 'templates/tech-developer-preview.jpg',
                'html_structure' => $this->getTechDeveloperHTML(),
                'css_content' => $this->getTechDeveloperCSS(),
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Academic Scholar',
                'slug' => 'academic-scholar',
                'thumbnail' => 'templates/academic-scholar-thumb.jpg',
                'preview_image' => 'templates/academic-scholar-preview.jpg',
                'html_structure' => $this->getAcademicScholarHTML(),
                'css_content' => $this->getAcademicScholarCSS(),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Sales Executive',
                'slug' => 'sales-executive',
                'thumbnail' => 'templates/sales-executive-thumb.jpg',
                'preview_image' => 'templates/sales-executive-preview.jpg',
                'html_structure' => $this->getSalesExecutiveHTML(),
                'css_content' => $this->getSalesExecutiveCSS(),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Startup Founder',
                'slug' => 'startup-founder',
                'thumbnail' => 'templates/startup-founder-thumb.jpg',
                'preview_image' => 'templates/startup-founder-preview.jpg',
                'html_structure' => $this->getStartupFounderHTML(),
                'css_content' => $this->getStartupFounderCSS(),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Digital Marketer',
                'slug' => 'digital-marketer',
                'thumbnail' => 'templates/digital-marketer-thumb.jpg',
                'preview_image' => 'templates/digital-marketer-preview.jpg',
                'html_structure' => $this->getDigitalMarketerHTML(),
                'css_content' => $this->getDigitalMarketerCSS(),
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Consultant Pro',
                'slug' => 'consultant-pro',
                'thumbnail' => 'templates/consultant-pro-thumb.jpg',
                'preview_image' => 'templates/consultant-pro-preview.jpg',
                'html_structure' => $this->getConsultantProHTML(),
                'css_content' => $this->getConsultantProCSS(),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }

    private function getModernProfessionalHTML()
    {
        return '<div class="resume-container">
            <header class="header">
                <div class="name-section">
                    <h1>{{personal_data.name}}</h1>
                    <p class="title">Professional Summary</p>
                </div>
                <div class="contact-section">
                    <div class="contact-item">
                        <span class="icon">📧</span>
                        <span>{{personal_data.email}}</span>
                    </div>
                    <div class="contact-item">
                        <span class="icon">📱</span>
                        <span>{{personal_data.phone}}</span>
                    </div>
                    <div class="contact-item">
                        <span class="icon">📍</span>
                        <span>{{personal_data.address}}</span>
                    </div>
                    <div class="contact-item" id="linkedin-item" style="display: none;">
                        <span class="icon">💼</span>
                        <span>{{personal_data.linkedin}}</span>
                    </div>
                </div>
            </header>

            <div class="summary-section" id="summary-section" style="display: none;">
                <h2>Professional Summary</h2>
                <p>{{personal_data.summary}}</p>
            </div>

            <section class="experience-section">
                <h2>Work Experience</h2>
                <div id="experiences-container"></div>
            </section>

            <section class="education-section">
                <h2>Education</h2>
                <div id="educations-container"></div>
            </section>

            <section class="skills-section">
                <h2>Skills</h2>
                <div class="skills-grid" id="skills-container"></div>
            </section>
        </div>';
    }

    private function getModernProfessionalCSS()
    {
        return '.resume-container {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid var(--primary-color, #3490dc);
        }

        .name-section h1 {
            font-size: 2.5em;
            margin: 0;
            color: var(--primary-color, #3490dc);
            font-weight: 700;
        }

        .name-section .title {
            font-size: 1.2em;
            color: #666;
            margin: 5px 0 0 0;
        }

        .contact-section {
            text-align: right;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        .contact-item .icon {
            margin-left: 8px;
            font-size: 1.1em;
        }

        .summary {
            margin-bottom: 30px;
        }

        .summary h2 {
            color: var(--primary-color, #3490dc);
            font-size: 1.3em;
            margin-bottom: 10px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }

        .experience, .education, .skills {
            margin-bottom: 30px;
        }

        .experience h2, .education h2, .skills h2 {
            color: var(--primary-color, #3490dc);
            font-size: 1.3em;
            margin-bottom: 15px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }

        .experience-item {
            margin-bottom: 20px;
        }

        .experience-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .experience-header h3 {
            margin: 0;
            font-size: 1.1em;
            color: #333;
        }

        .experience-meta {
            text-align: right;
            font-size: 0.9em;
            color: #666;
        }

        .experience-description {
            margin: 0;
            color: #555;
            line-height: 1.5;
        }

        .education-item {
            margin-bottom: 15px;
        }

        .education-item h3 {
            margin: 0 0 5px 0;
            font-size: 1.1em;
            color: #333;
        }

        .education-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .gpa {
            margin: 0;
            font-size: 0.9em;
            color: #666;
        }

        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .skill-tag {
            background-color: var(--primary-color, #3490dc);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }';
    }

    // Placeholder methods for other templates
    private function getCleanMinimalHTML() { return $this->getModernProfessionalHTML(); }
    private function getCleanMinimalCSS() { return $this->getModernProfessionalCSS(); }
    private function getCreativeDesignerHTML() { return $this->getModernProfessionalHTML(); }
    private function getCreativeDesignerCSS() { return $this->getModernProfessionalCSS(); }
    private function getExecutiveClassicHTML() { return $this->getModernProfessionalHTML(); }
    private function getExecutiveClassicCSS() { return $this->getModernProfessionalCSS(); }
    private function getTechDeveloperHTML() { return $this->getModernProfessionalHTML(); }
    private function getTechDeveloperCSS() { return $this->getModernProfessionalCSS(); }
    private function getAcademicScholarHTML() { return $this->getModernProfessionalHTML(); }
    private function getAcademicScholarCSS() { return $this->getModernProfessionalCSS(); }
    private function getSalesExecutiveHTML() { return $this->getModernProfessionalHTML(); }
    private function getSalesExecutiveCSS() { return $this->getModernProfessionalCSS(); }
    private function getStartupFounderHTML() { return $this->getModernProfessionalHTML(); }
    private function getStartupFounderCSS() { return $this->getModernProfessionalCSS(); }
    private function getDigitalMarketerHTML() { return $this->getModernProfessionalHTML(); }
    private function getDigitalMarketerCSS() { return $this->getModernProfessionalCSS(); }
    private function getConsultantProHTML() { return $this->getModernProfessionalHTML(); }
    private function getConsultantProCSS() { return $this->getModernProfessionalCSS(); }
}
