# Whistleblowing System

A comprehensive and secure whistleblowing system built with Laravel 12, featuring anonymous reporting, role-based access control, and advanced security measures.

## Features

### Core Features
- **Anonymous Reporting**: Secure anonymous submission with unique reference numbers
- **Role-Based Access Control**: Admin, Moderator, and Investigator roles
- **Real-time Tracking**: Track report status using reference numbers
- **File Attachments**: Secure file upload with malware scanning
- **Comments System**: Internal and external communication on reports
- **Advanced Analytics**: Comprehensive reporting and analytics dashboard
- **Multi-level Security**: Rate limiting, input sanitization, and security headers

### Security Features
- **API Authentication**: Laravel Sanctum with token-based authentication
- **Rate Limiting**: Configurable rate limiting per endpoint
- **Input Sanitization**: Automatic XSS protection and input cleaning
- **Security Headers**: Comprehensive HTTP security headers
- **File Security**: Malware detection and secure file storage
- **CSRF Protection**: Cross-site request forgery protection
- **SQL Injection Protection**: Eloquent ORM and parameterized queries

### User Roles

#### Admin
- Complete system access
- User management (create, update, delete users)
- Category management
- Report management
- Analytics and reporting
- System configuration

#### Moderator
- Report management
- Category management
- User performance monitoring
- Analytics access
- Report assignment

#### Investigator
- Access to assigned reports only
- Update report status
- Add comments and evidence
- View assigned analytics

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/PostgreSQL
- Node.js (for frontend assets)
- Git

### Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd whistleblowing-system
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=whistleblowing
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Create Storage Directories**
   ```bash
   php artisan storage:link
   mkdir -p storage/app/private/attachments
   ```

7. **Build Frontend Assets**
   ```bash
   npm run build
   ```

8. **Start the Server**
   ```bash
   php artisan serve
   ```

### Default Credentials

After seeding, you can login with these credentials:

- **Admin**: admin@whistleblowing.local / admin123!
- **Moderator**: moderator@whistleblowing.local / moderator123!
- **Investigator**: john.inv@whistleblowing.local / investigator123!
- **Investigator**: sarah.inv@whistleblowing.local / investigator123!

## API Documentation

### Authentication

All protected endpoints require authentication via Bearer token.

#### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "admin@whistleblowing.local",
    "password": "admin123!"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "System Administrator",
        "email": "admin@whistleblowing.local",
        "role": "admin"
    },
    "token": "1|laravel_sanctum_token...",
    "expires_at": "2024-02-15T10:30:00.000000Z"
}
```

#### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

### Public Endpoints (No Authentication Required)

#### Submit Anonymous Report
```http
POST /api/v1/reports
Content-Type: multipart/form-data

{
    "category_id": 1,
    "title": "Report Title",
    "description": "Detailed description of the incident",
    "incident_location": "Office Building A",
    "incident_date": "2024-01-15",
    "incident_time": "14:30",
    "urgency_level": "high",
    "is_anonymous": true,
    "attachments[]": [file1, file2]
}
```

#### Track Report
```http
GET /api/v1/reports/{reference}/track
```

#### Get Public Categories
```http
GET /api/v1/categories/public
```

### Protected Endpoints

#### Reports Management

**List Reports** (Admin/Moderator/Investigator)
```http
GET /api/v1/reports?status=submitted&category_id=1&page=1
Authorization: Bearer {token}
```

**Get Report Details**
```http
GET /api/v1/reports/{id}
Authorization: Bearer {token}
```

**Update Report Status**
```http
PATCH /api/v1/reports/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
    "status": "investigating",
    "resolution_details": "Investigation in progress"
}
```

**Assign Report**
```http
PATCH /api/v1/reports/{id}/assign
Authorization: Bearer {token}
Content-Type: application/json

{
    "assigned_to_user_id": 3
}
```

#### Comments

**Add Comment**
```http
POST /api/v1/reports/{id}/comments
Authorization: Bearer {token}
Content-Type: application/json

{
    "comment": "Investigation update",
    "is_internal": true
}
```

**Get Comments**
```http
GET /api/v1/reports/{id}/comments
Authorization: Bearer {token}
```

#### User Management (Admin Only)

**List Users**
```http
GET /api/v1/users?role=investigator&search=john
Authorization: Bearer {token}
```

**Create User**
```http
POST /api/v1/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "New User",
    "email": "user@example.com",
    "password": "SecurePass123!",
    "password_confirmation": "SecurePass123!",
    "role": "investigator",
    "department": "Legal"
}
```

#### Analytics (Admin/Moderator)

**Dashboard Stats**
```http
GET /api/v1/dashboard/stats
Authorization: Bearer {token}
```

**Analytics Overview**
```http
GET /api/v1/analytics/overview?from=2024-01-01&to=2024-01-31
Authorization: Bearer {token}
```

**Trends Data**
```http
GET /api/v1/analytics/trends?period=last_30_days&group_by=day
Authorization: Bearer {token}
```

### Rate Limiting

Different endpoints have different rate limits:

- **Authentication**: 5 requests per minute
- **Public Report Submission**: 10 requests per minute  
- **Protected Endpoints**: 60 requests per minute

Rate limit headers are included in responses:
- `X-RateLimit-Limit`: Maximum requests allowed
- `X-RateLimit-Remaining`: Remaining requests
- `X-RateLimit-Reset`: Unix timestamp when limit resets

## Database Schema

### Key Tables

#### Users
- Authentication and authorization
- Role-based permissions (admin, moderator, investigator)
- Activity tracking

#### Reports
- Complete incident information
- Status tracking and workflow
- Anonymous and identified reporting
- File attachments support

#### Categories
- Configurable report categories
- Active/inactive status
- Sorting and organization

#### Comments
- Internal and external communication
- User attribution
- Threaded discussions

## Security Considerations

### File Upload Security
- File type validation
- Size limitations (10MB per file)
- Malware detection
- Secure storage in private directories
- Filename sanitization

### Data Protection
- Input sanitization and validation
- SQL injection prevention
- XSS protection
- CSRF protection
- Secure password hashing

### API Security
- Bearer token authentication
- Rate limiting per endpoint
- Request validation
- Security headers
- CORS configuration

### Anonymous Reporting
- No tracking of IP addresses for anonymous reports
- Secure reference number generation
- Optional contact information
- Privacy-first design

## Configuration

### Environment Variables

```env
# Application
APP_NAME="Whistleblowing System"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://whistleblowing.example.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=whistleblowing
DB_USERNAME=username
DB_PASSWORD=password

# API Authentication
SANCTUM_TOKEN_EXPIRATION=43200  # 30 days in minutes
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,your-domain.com

# Security
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true

# File Storage
FILESYSTEM_DISK=local
```

### Security Headers

The system automatically adds security headers:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY`
- `X-XSS-Protection: 1; mode=block`
- `Content-Security-Policy`
- `Referrer-Policy: strict-origin-when-cross-origin`

## Deployment

### Production Checklist

1. **Environment Configuration**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Configure proper database credentials
   - Set secure session configuration

2. **Security Setup**
   - Configure HTTPS
   - Set up proper file permissions
   - Configure firewall rules
   - Enable fail2ban for brute force protection

3. **Database Optimization**
   - Run migrations
   - Optimize database queries
   - Set up database backups

4. **Monitoring**
   - Set up application logging
   - Configure error reporting
   - Monitor system performance
   - Set up uptime monitoring

## Support

For technical support or questions about the whistleblowing system, please contact the system administrators.

## License

This whistleblowing system is proprietary software. All rights reserved.
