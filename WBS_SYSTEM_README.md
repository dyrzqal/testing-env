# WBS (Whistleblower System) - Role-Based Dashboard System

## Overview
Sistem WBS yang lengkap dengan dashboard khusus untuk setiap role user (Admin, Moderator, Investigator) yang memungkinkan manajemen laporan dan investigasi yang efektif.

## Features

### 🔐 Role-Based Access Control
- **Admin**: Akses penuh ke semua fitur sistem
- **Moderator**: Manajemen laporan dan kategori
- **Investigator**: Fokus pada investigasi laporan yang ditugaskan

### 📊 Dashboard Features

#### Admin Dashboard
- Statistik lengkap sistem (total laporan, user, dll)
- Manajemen user dan role
- Overview semua laporan
- Quick actions untuk manajemen sistem
- Analytics dan reporting

#### Moderator Dashboard
- Review dan moderasi laporan
- Manajemen kategori laporan
- Statistik laporan berdasarkan status
- Tools untuk validasi laporan

#### Investigator Dashboard
- Laporan yang ditugaskan
- Tools investigasi (Evidence Tracker, Case Notes, Timeline)
- Update status investigasi
- Progress tracking

### 🚀 Quick Actions
- Create new reports
- Manage categories
- User management
- Export data
- View analytics

## System Architecture

### Controllers
- `DashboardController`: Mengelola dashboard berdasarkan role
- `ReportController`: Manajemen laporan
- `PublicReportController`: Laporan publik

### Models
- `User`: User dengan role dan permissions
- `Report`: Laporan dengan status dan kategori
- `Category`: Kategori laporan
- `ReportComment`: Komentar pada laporan
- `ReportAttachment`: Lampiran laporan

### Middleware
- `RoleMiddleware`: Validasi role dan permissions
- Authorization menggunakan Laravel Policies

### Views
- Dashboard templates untuk setiap role
- Responsive design dengan Tailwind CSS
- Interactive components

## Installation & Setup

### Prerequisites
- PHP 8.1+
- Laravel 11+
- MySQL/PostgreSQL
- Composer
- Node.js & NPM

### Setup Steps
1. Clone repository
2. Install dependencies: `composer install && npm install`
3. Copy `.env.example` to `.env`
4. Configure database connection
5. Run migrations: `php artisan migrate`
6. Seed database: `php artisan db:seed`
7. Build assets: `npm run build`

### Database Seeding
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=CategorySeeder
```

## Usage

### Admin Access
- Login dengan role admin
- Akses dashboard admin di `/admin`
- Manajemen user, kategori, dan sistem

### Moderator Access
- Login dengan role moderator
- Akses dashboard moderator di `/admin`
- Review dan moderasi laporan

### Investigator Access
- Login dengan role investigator
- Akses dashboard investigator di `/admin`
- Fokus pada investigasi laporan

## API Endpoints

### Dashboard
- `GET /admin` - Dashboard berdasarkan role
- `GET /admin/analytics` - Analytics (Admin/Moderator)
- `GET /admin/analytics/export` - Export data

### Reports
- `GET /admin/reports` - List semua laporan
- `POST /admin/reports` - Create laporan baru
- `GET /admin/reports/{id}` - View laporan
- `PUT /admin/reports/{id}` - Update laporan
- `DELETE /admin/reports/{id}` - Delete laporan
- `PATCH /admin/reports/{id}/status` - Update status

### Categories
- `GET /admin/categories` - List kategori
- `POST /admin/categories` - Create kategori
- `PUT /admin/categories/{id}` - Update kategori
- `DELETE /admin/categories/{id}` - Delete kategori

### Users
- `GET /admin/users` - List user (Admin only)
- `POST /admin/users` - Create user (Admin only)
- `PUT /admin/users/{id}` - Update user (Admin only)
- `PATCH /admin/users/{id}/status` - Toggle status (Admin only)

## Security Features

### Authentication
- Laravel Breeze authentication
- Role-based access control
- Middleware protection

### Authorization
- Laravel Policies untuk setiap model
- Role validation
- Permission checking

### Data Protection
- CSRF protection
- Input sanitization
- SQL injection prevention

## Customization

### Adding New Roles
1. Update User model dengan role baru
2. Add role checking methods
3. Update middleware dan policies
4. Create dashboard template

### Custom Dashboard Widgets
1. Extend DashboardController
2. Add new data methods
3. Create view components
4. Update dashboard templates

## Troubleshooting

### Common Issues
- **403 Forbidden**: Check user role dan permissions
- **Dashboard not loading**: Verify middleware registration
- **Role not working**: Check database role values

### Debug Mode
Enable debug mode in `.env`:
```
APP_DEBUG=true
```

## Contributing
1. Fork repository
2. Create feature branch
3. Make changes
4. Test thoroughly
5. Submit pull request

## License
This project is licensed under the MIT License.

## Support
Untuk dukungan teknis, silakan buat issue di repository atau hubungi tim development.

---

**Note**: Sistem ini dirancang untuk keamanan tinggi dan compliance dengan regulasi whistleblower. Pastikan semua security measures aktif dan ter-update.