# WBS System Testing Instructions

## Quick Start Testing

### 1. Database Setup
```bash
# Run migrations
php artisan migrate:fresh

# Seed database with test data
php artisan db:seed
```

### 2. Test User Credentials

#### Admin Access
- **Email:** admin@wbs.com
- **Password:** password
- **Role:** Admin
- **Access:** Full system access

#### Moderator Access
- **Email:** moderator@wbs.com
- **Password:** password
- **Role:** Moderator
- **Access:** Reports, Categories, Analytics

#### Investigator Access
- **Email:** investigator@wbs.com
- **Password:** password
- **Role:** Investigator
- **Access:** Assigned reports, Investigation tools

#### Additional Test Users
- **Email:** john.doe@wbs.com (Investigator)
- **Email:** jane.smith@wbs.com (Moderator)
- **Email:** mike.johnson@wbs.com (Investigator)
- **Email:** sarah.wilson@wbs.com (Moderator)
- **Email:** david.brown@wbs.com (Investigator)
- **Email:** lisa.davis@wbs.com (Moderator)

**All passwords:** password

### 3. Testing Scenarios

#### Admin Dashboard Testing
1. Login as admin@wbs.com
2. Navigate to `/admin`
3. Verify all statistics are displayed
4. Check user management access
5. Test analytics page
6. Verify category management

#### Moderator Dashboard Testing
1. Login as moderator@wbs.com
2. Navigate to `/admin`
3. Verify moderator-specific dashboard
4. Test report review functionality
5. Check category management
6. Access analytics (should work)

#### Investigator Dashboard Testing
1. Login as investigator@wbs.com
2. Navigate to `/admin`
3. Verify investigator-specific dashboard
4. Check assigned reports
5. Test investigation tools
6. Verify status update functionality

### 4. Feature Testing Checklist

#### Dashboard Features
- [ ] Role-based dashboard display
- [ ] Statistics cards
- [ ] Recent reports table
- [ ] Quick actions
- [ ] Navigation menu

#### Report Management
- [ ] View all reports
- [ ] Filter by status
- [ ] Assign reports to investigators
- [ ] Update report status
- [ ] Add comments

#### User Management (Admin Only)
- [ ] View all users
- [ ] Create new users
- [ ] Update user information
- [ ] Toggle user status
- [ ] Role assignment

#### Category Management
- [ ] View categories
- [ ] Create new categories
- [ ] Edit categories
- [ ] Delete categories

#### Analytics (Admin/Moderator)
- [ ] View analytics page
- [ ] Check charts and graphs
- [ ] Export functionality
- [ ] Date filtering

### 5. Common Test Cases

#### Permission Testing
1. **Admin Access Test**
   - Login as admin
   - Verify access to all sections
   - Test user management

2. **Moderator Access Test**
   - Login as moderator
   - Verify limited access
   - Test report moderation

3. **Investigator Access Test**
   - Login as investigator
   - Verify report assignment
   - Test investigation tools

#### Navigation Testing
1. **Desktop Navigation**
   - Test main menu items
   - Verify role-based menu display
   - Check active states

2. **Mobile Navigation**
   - Test responsive menu
   - Verify mobile-specific features
   - Check touch interactions

#### Data Display Testing
1. **Dashboard Statistics**
   - Verify correct counts
   - Check real-time updates
   - Test data accuracy

2. **Report Tables**
   - Verify pagination
   - Test sorting functionality
   - Check search/filter

### 6. Error Testing

#### Permission Errors
- Try accessing admin features as moderator
- Attempt user management as investigator
- Test unauthorized analytics access

#### Data Validation
- Submit forms with invalid data
- Test required field validation
- Verify error message display

#### System Errors
- Test with invalid IDs
- Check 404 error handling
- Verify 403 permission errors

### 7. Performance Testing

#### Dashboard Loading
- Measure initial load time
- Test with large datasets
- Verify responsive behavior

#### Database Queries
- Check query performance
- Test with multiple users
- Verify caching effectiveness

### 8. Browser Testing

#### Supported Browsers
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

#### Responsive Testing
- Desktop (1920x1080)
- Tablet (768x1024)
- Mobile (375x667)

### 9. Security Testing

#### Authentication
- Test login/logout
- Verify session management
- Check password requirements

#### Authorization
- Test role-based access
- Verify permission checks
- Check CSRF protection

#### Data Protection
- Verify input sanitization
- Test SQL injection prevention
- Check XSS protection

### 10. Troubleshooting

#### Common Issues
1. **Dashboard Not Loading**
   - Check database connection
   - Verify middleware registration
   - Check route definitions

2. **Permission Errors**
   - Verify user role in database
   - Check middleware configuration
   - Verify policy registration

3. **Data Not Displaying**
   - Check database seeding
   - Verify model relationships
   - Check view data passing

#### Debug Mode
Enable debug mode in `.env`:
```
APP_DEBUG=true
APP_ENV=local
```

### 11. Testing Tools

#### Laravel Testing
```bash
# Run feature tests
php artisan test

# Run specific test
php artisan test --filter DashboardTest

# Run with coverage
php artisan test --coverage
```

#### Browser Testing
- Laravel Dusk for browser automation
- Selenium for cross-browser testing
- Manual testing checklist

### 12. Reporting Issues

When reporting issues, include:
1. **Environment Details**
   - Laravel version
   - PHP version
   - Database type and version
   - Browser and version

2. **Steps to Reproduce**
   - Detailed step-by-step instructions
   - Screenshots or screen recordings
   - Expected vs actual behavior

3. **Error Information**
   - Error messages
   - Stack traces
   - Log files

4. **Test Data**
   - User credentials used
   - Sample data involved
   - Database state

---

**Note:** This testing guide covers the core functionality. For comprehensive testing, consider implementing automated tests and continuous integration.