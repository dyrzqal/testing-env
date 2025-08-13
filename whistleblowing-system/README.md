# 🔊 Whistleblowing System (WBS)

A comprehensive, secure, and anonymous whistleblowing system built with Laravel 12. This system allows individuals to report misconduct, unethical behavior, and violations while maintaining complete anonymity and confidentiality.

## 🌟 Features

### 🔒 Anonymous Reporting
- **Complete Anonymity**: Reports can be submitted without revealing identity
- **Secure File Uploads**: Support for documents, images, videos, and audio evidence
- **Reference Number Tracking**: Unique tracking codes for report status monitoring
- **Multiple Categories**: Organized reporting categories for different types of misconduct

### 📊 Admin Management
- **Role-Based Access Control**: Admin, Moderator, and Investigator roles
- **Report Management**: Complete workflow from submission to resolution
- **Status Tracking**: Real-time status updates and investigation progress
- **Comment System**: Internal and public communication tracking

### 🎨 Modern Interface
- **Responsive Design**: Mobile-friendly interface using Tailwind CSS
- **Interactive UI**: Enhanced user experience with Alpine.js
- **Professional Styling**: Clean, modern design focused on user trust
- **Accessibility**: WCAG compliant design for all users

### 🛡️ Security Features
- **SSL/TLS Encryption**: Secure data transmission
- **CSRF Protection**: Cross-site request forgery prevention
- **File Security**: Secure file storage and validation
- **SQL Injection Prevention**: Parameterized queries and validation
- **XSS Protection**: Cross-site scripting prevention

## 📋 Report Categories

- **Corruption & Bribery**: Financial misconduct and corrupt practices
- **Fraud & Financial Misconduct**: Embezzlement and financial irregularities
- **Workplace Harassment**: Sexual harassment, bullying, discrimination
- **Safety & Health Violations**: Workplace safety and environmental concerns
- **Data & Privacy Breach**: Information security violations
- **Ethics & Compliance**: Policy violations and ethical breaches
- **Conflict of Interest**: Undisclosed conflicts and favoritism
- **Theft & Misuse of Resources**: Company property and resource abuse
- **Regulatory Violations**: Industry regulation breaches
- **Other Misconduct**: General misconduct not covered above

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd whistleblowing-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   
   # Run migrations and seeders
   php artisan migrate --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the system.

## 👥 Default Users

After running the seeders, you'll have these default admin accounts:

| Role | Email | Password | Department |
|------|-------|----------|------------|
| Admin | admin@whistleblowing.com | admin123 | IT Administration |
| Moderator | moderator@whistleblowing.com | moderator123 | Human Resources |
| Investigator | investigator@whistleblowing.com | investigator123 | Internal Audit |

⚠️ **Change these passwords immediately in production!**

## 🔧 Configuration

### File Uploads
- Maximum file size: 10MB per file
- Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF, MP4, AVI, MOV, MP3, WAV
- Storage: Files are stored securely with encrypted names

### Email Configuration
Update `.env` with your email settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### Database Configuration
For production, use a robust database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=whistleblowing
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

## 🛣️ API Routes

### Public Routes
- `GET /` - Homepage
- `GET /report/submit` - Submit report form
- `POST /report/submit` - Process report submission
- `GET /report/track` - Track report form
- `POST /report/track` - Process report tracking
- `GET /report/success/{reference}` - Success page

### Admin Routes (Authenticated)
- `GET /admin` - Admin dashboard
- `GET /admin/reports` - Reports management
- `GET /admin/users` - User management (Admin only)
- `GET /admin/categories` - Category management (Admin/Moderator)
- `GET /admin/analytics` - Analytics and reports

## 📊 Database Schema

### Key Tables
- **reports**: Main report data with comprehensive tracking
- **categories**: Report categorization system
- **users**: Admin user management with roles
- **report_attachments**: File upload tracking
- **report_comments**: Investigation progress tracking

### Relationships
- One report belongs to one category
- One report can have multiple attachments
- One report can have multiple comments
- One user can be assigned multiple reports

## 🔐 Security Best Practices

1. **Environment Variables**: Keep sensitive data in `.env` file
2. **File Permissions**: Secure storage directory permissions
3. **HTTPS**: Always use HTTPS in production
4. **Regular Updates**: Keep Laravel and dependencies updated
5. **Database Security**: Use strong database credentials
6. **Backup Strategy**: Regular database and file backups

## 🎯 Usage

### For Reporters
1. Visit the homepage
2. Click "Submit a Report"
3. Fill out the detailed form
4. Upload any evidence files
5. Choose anonymous or identified submission
6. Receive a unique reference number
7. Use the reference number to track progress

### For Administrators
1. Login with admin credentials
2. Access the admin dashboard
3. Review and manage reports
4. Update report statuses
5. Add investigation comments
6. Generate analytics reports

## 🚀 Deployment

### Production Checklist
- [ ] Update `.env` with production settings
- [ ] Set `APP_ENV=production`
- [ ] Configure proper database
- [ ] Set up email service
- [ ] Configure file storage
- [ ] Enable HTTPS
- [ ] Set up regular backups
- [ ] Configure monitoring

### Server Requirements
- PHP 8.2+ with required extensions
- Web server (Apache/Nginx)
- Database (MySQL/PostgreSQL)
- SSL certificate
- Sufficient storage for file uploads

## 📈 Analytics

The system provides comprehensive analytics including:
- Report volume trends
- Category distribution
- Response time metrics
- Resolution rates
- User activity tracking

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is open-sourced under the [MIT License](LICENSE).

## 📞 Support

For support and questions:
- Create an issue in the GitHub repository
- Review the documentation
- Check the FAQ section

## 🔄 Version History

- **v1.0.0** - Initial release with core whistleblowing functionality
- Comprehensive anonymous reporting system
- Admin dashboard and management tools
- File upload and tracking capabilities
- Role-based access control

---

**Built with ❤️ using Laravel 12, Tailwind CSS, and Alpine.js**
