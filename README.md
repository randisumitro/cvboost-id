# CVBoost.id - Professional Resume Builder with ATS Checker

<p align="center">
    <img src="https://via.placeholder.com/400x100/3490dc/ffffff?text=CVBoost.id" alt="CVBoost.id Logo" width="400">
</p>

Create professional resumes that pass ATS with our AI-powered resume builder. Build stunning resumes in minutes with expert-designed templates.

## 🚀 Features

- **Multi-Step Resume Builder** - 6-step guided form with validation
- **ATS Checker** - AI-powered scoring system to optimize your resume
- **10+ Professional Templates** - ATS-friendly templates for every industry
- **Live Preview** - Real-time preview as you build your resume
- **PDF Export** - High-quality PDF generation with watermark control
- **Subscription System** - Free and premium plans with advanced features
- **Ad Integration** - Google AdSense integration for monetization
- **SEO Optimized** - Built with SEO best practices and structured data

## 🛠 Tech Stack

- **Backend**: Laravel 11, PHP 8.1+
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5, JavaScript, jQuery
- **PDF Generation**: DomPDF
- **Queue System**: Laravel Queues
- **Authentication**: Laravel Breeze
- **Payment**: Midtrans/Xendit Integration Ready

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Node.js & NPM (for asset compilation)
- Web server (Apache/Nginx)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd cvboost-id
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
```

Edit your `.env` file and configure:

```env
APP_NAME="CVBoost"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cvboost_id
DB_USERNAME=root
DB_PASSWORD=

# Payment Gateway (Optional)
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key

# Google Login (Optional)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Google Analytics (Optional)
GA_MEASUREMENT_ID=your_ga_measurement_id
```

### 4. Database Setup

```bash
php artisan migrate
php artisan db:seed --class=TemplateSeeder
```

### 5. Link Storage

```bash
php artisan storage:link
```

### 6. Compile Assets

```bash
npm run build
```

### 7. Start the Application

```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🗄 Database Structure

The application uses the following main tables:

- **users** - User accounts with subscription status
- **templates** - Resume templates with HTML/CSS structure
- **resumes** - Created resumes with user data
- **subscriptions** - Payment and subscription records
- **page_views** - Analytics tracking
- **ads_impressions** - Ad tracking

## 🎯 Core Features

### Resume Builder

1. **Personal Information** - Name, email, phone, address, LinkedIn, portfolio
2. **Professional Summary** - Brief career overview (max 500 characters)
3. **Work Experience** - Multiple positions with dates and descriptions
4. **Education** - Academic background with GPA support
5. **Skills** - Tag-based skill system (3-10 skills)
6. **Template Selection** - Choose and customize templates

### ATS Checker

- **Score Calculation** (0-100): Based on sections, formatting, keywords, readability
- **Keyword Analysis**: Job-specific keyword density checking
- **Format Validation**: Consistent date formats and ATS-friendly structure
- **Suggestions**: Actionable recommendations for improvement

### Subscription Plans

**Free Plan:**
- 3 resumes maximum
- 3 ATS scans
- Basic templates
- Watermarked PDFs
- Ad-supported

**Premium Plan (Rp 49,000/month):**
- Unlimited resumes
- Unlimited ATS scans
- All templates
- No watermarks
- DOCX export
- Priority support

## 🔄 Queue System

The application uses Laravel queues for:

- **PDF Generation** - Background processing for large PDF files
- **ATS Scanning** - Complex ATS analysis processing
- **Email Notifications** - User notifications and marketing

### Scheduled Jobs

```bash
# Daily cleanup of temporary PDFs
php artisan resume:clean-temp-pdfs

# Check expired subscriptions
php artisan subscription:check-expired

# Reset free user limits (monthly)
php artisan subscription:reset-free-limits

# Generate sitemap (weekly)
php artisan sitemap:generate
```

## 📱 Ad Integration

The application integrates with Google AdSense:

- **Banner Ads** - Header and footer placements
- **Sidebar Ads** - Template gallery and blog pages
- **Interstitial Ads** - Before download for free users
- **Tracking** - Impression and click tracking

## 🔧 Configuration

### Payment Gateway Setup

1. **Midtrans Integration**
   - Add your Midtrans credentials to `.env`
   - Configure webhook endpoints
   - Test with sandbox mode first

2. **Xendit Integration** (Alternative)
   - Similar setup process
   - Update payment controller as needed

### Google Services

1. **Google OAuth**
   - Create Google Cloud project
   - Enable Google+ API
   - Configure OAuth 2.0 credentials

2. **Google Analytics**
   - Create GA4 property
   - Add measurement ID to `.env`
   - Configure tracking events

## 🚀 Deployment

### Alwaysdata Deployment

1. **Prepare Environment**
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Set Up Alwaysdata**
   - Create MySQL database
   - Upload files to `/www/cvboost/`
   - Configure environment variables
   - Set up cron jobs for scheduled tasks

3. **Cron Jobs Setup**
   ```bash
   # Queue worker
   * * * * * cd /path/to/project && php artisan queue:work --sleep=3 --tries=3

   # Scheduled tasks
   0 2 * * * cd /path/to/project && php artisan resume:clean-temp-pdfs
   0 3 * * * cd /path/to/project && php artisan subscription:check-expired
   0 4 1 * * cd /path/to/project && php artisan subscription:reset-free-limits
   0 5 * * 0 cd /path/to/project && php artisan sitemap:generate
   ```

### Environment Variables for Production

```env
APP_NAME="CVBoost"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.alwaysdata.net

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=mysql-youraccount.alwaysdata.net
DB_PORT=3306
DB_DATABASE=cvboost_db
DB_USERNAME=youraccount
DB_PASSWORD=yourpassword
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter ResumeTest

# Generate test coverage
php artisan test --coverage
```

## 📊 API Endpoints

### Resume Management
- `POST /api/resumes` - Create new resume
- `GET /api/resumes/{id}` - Get resume details
- `PUT /api/resumes/{id}` - Update resume
- `POST /api/resumes/{id}/generate-pdf` - Generate PDF
- `POST /api/resumes/{id}/ats-score` - Check ATS score

### Templates
- `GET /api/templates` - List available templates
- `GET /api/templates/{id}` - Get template details
- `GET /api/templates/{id}/preview` - Preview template

### Subscriptions
- `POST /api/subscription/create` - Create subscription
- `GET /api/subscription/status` - Check subscription status
- `POST /api/subscription/webhook/midtrans` - Payment webhook

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 🆘 Support

For support and questions:

- 📧 Email: support@cvboost.id
- 🐛 Issues: [GitHub Issues](https://github.com/yourusername/cvboost-id/issues)
- 📖 Documentation: [Wiki](https://github.com/yourusername/cvboost-id/wiki)

## 🌟 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework For Web Artisans
- [Bootstrap](https://getbootstrap.com) - The most popular HTML, CSS, and JS framework
- [DomPDF](https://github.com/dompdf/dompdf) - HTML to PDF converter
- [Font Awesome](https://fontawesome.com) - The iconic font and CSS toolkit

---

<p align="center">
    Made with ❤️ in Indonesia
</p>
